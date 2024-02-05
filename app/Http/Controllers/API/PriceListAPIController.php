<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePriceListAPIRequest;
use App\Http\Requests\API\UpdatePriceListAPIRequest;
use App\Models\PriceList;
use App\Repositories\PriceListRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PriceListResource;
use Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Imports\PriceListImport;
use App\Models\CurrencyMaster;
use App\Services\GeneralService;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;

/**
 * Class PriceListController
 * @package App\Http\Controllers\API
 */

class PriceListAPIController extends AppBaseController
{
    /** @var  PriceListRepository */
    private $priceListRepository;

    public function __construct(PriceListRepository $priceListRepo)
    {
        $this->priceListRepository = $priceListRepo;
    }

    /**
     * Display a listing of the PriceList.
     * GET|HEAD /priceLists
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $priceLists = $this->priceListRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(PriceListResource::collection($priceLists), 'Price Lists retrieved successfully');
    }

    /**
     * Store a newly created PriceList in storage.
     * POST /priceLists
     *
     * @param CreatePriceListAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePriceListAPIRequest $request)
    {
        $input = $request->all();

        $priceList = $this->priceListRepository->create($input);

        return $this->sendResponse(new PriceListResource($priceList), 'Price List saved successfully');
    }

    /**
     * Display the specified PriceList.
     * GET|HEAD /priceLists/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PriceList $priceList */
        $priceList = $this->priceListRepository->find($id);

        if (empty($priceList)) {
            return $this->sendError('Price List not found');
        }

        return $this->sendResponse(new PriceListResource($priceList), 'Price List retrieved successfully');
    }

    /**
     * Update the specified PriceList in storage.
     * PUT/PATCH /priceLists/{id}
     *
     * @param int $id
     * @param UpdatePriceListAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePriceListAPIRequest $request)
    {
        $input = $request->all();

        /** @var PriceList $priceList */
        $priceList = $this->priceListRepository->find($id);

        if (empty($priceList)) {
            return $this->sendError('Price List not found');
        }

        $priceList = $this->priceListRepository->update($input, $id);

        return $this->sendResponse(new PriceListResource($priceList), 'PriceList updated successfully');
    }

    /**
     * Remove the specified PriceList from storage.
     * DELETE /priceLists/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        $input = $request->all();
        /** @var PriceList $priceList */
        $priceList = $this->priceListRepository->find($input['id']);

        if (empty($priceList)) {
            return $this->sendError('Price List not found');
        }

        $priceList->delete();

        return $this->sendSuccess('Price List deleted successfully');
    }
    public function excelBulkUpload(Request $request)
    {

        DB::beginTransaction();
        try {
            $input = $request->all();
            $excelUpload = $input['itemExcelUpload'];
            $input = Arr::except($request->all(), 'itemExcelUpload');
            $input = $this->convertArrayToValue($input);
            $decodeFile = base64_decode($excelUpload[0]['file']);
            $originalFileName = $excelUpload[0]['filename'];
            $extension = $excelUpload[0]['filetype'];
            $size = $excelUpload[0]['size'];
            $allowedExtensions = ['xlsx', 'xls'];
            $validateExcel = false;
            if (!in_array($extension, $allowedExtensions)) {
                return $this->sendError('This type of file not allow to upload.you can only upload .xlsx (or) .xls', 500);
            }
            if ($size > 20000000) {
                return $this->sendError('The maximum size allow to upload is 20 MB', 500);
            }
            if($originalFileName!='price_list_template.xlsx'){ 
                return $this->sendError('Not a valid Excel', 500);
            }

            $disk = 'local';
            Storage::disk($disk)->put($originalFileName, $decodeFile);
            $array = (new PriceListImport)->toArray($originalFileName);  

            if(count($array[0])==0){ 
                unlink(storage_path('app/' . $originalFileName));
                return $this->sendError('No Records found!', 500);
            }

            foreach ($array[0] as $key => $value) {
                if (isset($value['item_code']) && isset($value['item_description']) && isset($value['part_number']) && isset($value['uom'])
                && isset($value['lead_time_days']) && isset($value['unit_price'] ) && isset($value['currency']) && isset($value['from_date_ddmmyyyy'])
                && isset($value['to_date_ddmmyyyy'])) {
                    $validateExcel = true;
                }
             }
            
             if (!$validateExcel) {
                 unlink(storage_path('app/' . $originalFileName));
                 return $this->sendError('Excel is not valid, template deafult fields are modified', 500);
             }
 
            if (count($array[0]) > 0) {
                $res = $this->storeExcelUpload($request, $request->user(), $array[0], $input['tenantId'], $originalFileName);
            }
           

            DB::commit();
            if ($res['success']) {
                unlink(storage_path('app/' . $originalFileName));
                return ['success' => true, 'message' => $res['message'], 'data' =>  $res['data']];
            } else {
                unlink(storage_path('app/' . $originalFileName));
                return ['success' => false, 'message' => $res['message'], 'data' =>  $res['data']];
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            unlink(storage_path('app/' . $originalFileName));
            return $this->sendError($exception->getMessage());
        }
    }
    public function storeExcelUpload($request, $user, $excelUploadData, $tenantId, $originalFileName)
    {
        $input = $request->all();
        $itemCodeRequired = [];
        $skipRecords = [];
        $successCount = 0;
        $dataToUpload = array_unique(array_column($excelUploadData, 'item_code'));
        $excelUploadDataN = (array_intersect_key($excelUploadData, $dataToUpload));
        $diff = array_diff(array_map('json_encode', $excelUploadData), array_map('json_encode', $excelUploadDataN));
        $duplicates = array_map('json_decode', $diff);
        $upload_arr = [];
        if (!empty($excelUploadDataN)) {
            $x = 0;
            DB::beginTransaction();
            try {
                foreach ($excelUploadDataN as $key => $val) {
                    if (!isset($val['item_code']) || $val['item_code'] == null) {
                        $data['errorMsgType']  = 1;
                        $data['msg'] = 'Line no ' . ($key + 1) . ' Item code is required';
                        array_push($itemCodeRequired, $data);
                    }
                    if (!isset($val['item_description']) || $val['item_description'] == null) {
                        $data['errorMsgType']  = 1;
                        $data['msg'] = 'Line no ' . ($key + 1) . ' Item description is required';
                        array_push($itemCodeRequired, $data);
                    }
                    if (!isset($val['uom']) || $val['uom'] == null) {
                        $data['errorMsgType']  = 1;
                        $data['msg'] = 'Line no ' . ($key + 1) . ' UOM is required';
                        array_push($itemCodeRequired, $data);
                    }
                    if (!isset($val['currency']) || $val['currency'] == null) {
                        $data['errorMsgType']  = 1;
                        $data['msg'] = 'Line no ' . ($key + 1) . ' Currency is required';
                        array_push($itemCodeRequired, $data);
                    }
                    if (!isset($val['unit_price']) || $val['unit_price'] == null) {
                        $data['errorMsgType']  = 1;
                        $data['msg'] = 'Line no ' . ($key + 1) . ' Unit price is required';
                        array_push($itemCodeRequired, $data);
                    }

                    if (is_string($val['from_date_ddmmyyyy']) && $val['from_date_ddmmyyyy'] != null) {
                        $data['errorMsgType']  = 1;
                        $data['msg'] = 'Line no ' . ($key + 1) . ' From date format is incorrect';
                        array_push($itemCodeRequired, $data);
                    }

                    if (is_string($val['to_date_ddmmyyyy']) && $val['to_date_ddmmyyyy'] != null) {
                        $data['errorMsgType']  = 1;
                        $data['msg'] = 'Line no ' . ($key + 1) . ' To date format is incorrect';
                        array_push($itemCodeRequired, $data);
                    }

                    if ((!is_string($val['from_date_ddmmyyyy']) && $val['from_date_ddmmyyyy'] != null) && (!is_string($val['to_date_ddmmyyyy']) && $val['to_date_ddmmyyyy'] != null)) {
                        $from_date = new Carbon(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val['from_date_ddmmyyyy']));
                        $from_date->format('Y-m-d');
                        $to_date = new Carbon(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val['to_date_ddmmyyyy']));
                        $to_date->format('Y-m-d');

                        if ($from_date > $to_date) {
                            $data['errorMsgType']  = 1;
                            $data['msg'] = 'Line no ' . ($key + 1) . ' To date is less than from date';
                            array_push($itemCodeRequired, $data);
                        }
                    }

                    $x++;


                    $priceList = PriceList::select('id', 'item_code')
                        ->whereRaw("LOWER(item_code) = LOWER('{$val['item_code']}')")
                        ->where('tenant_id', $input['tenantId'])
                        ->whereNotNull('id')
                        ->first();

                    if ($priceList) {
                        $data['success'] = 2;  // skipped 
                        $data['msg'] = 'Item code ' . $val['item_code'] . ' already exists';
                        array_push($skipRecords, $data);
                    }

                    if (isset($priceList['item_code']) != $val['item_code']) {
                        $currency = CurrencyMaster::select('id')->where('currency_code', $val['currency'])->first();

                        if ((!is_string($val['from_date_ddmmyyyy'])) && $val['from_date_ddmmyyyy'] != null) {
                            $from_date_new = new Carbon(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val['from_date_ddmmyyyy']));
                            $uploadExcelData['from_date'] = $from_date_new->format('Y-m-d');
                        } else {
                            $uploadExcelData['from_date'] = null;
                        }
                        if ((!is_string($val['to_date_ddmmyyyy'])) && $val['to_date_ddmmyyyy'] != null) {
                            $to_date_new = new Carbon(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val['to_date_ddmmyyyy']));
                            $uploadExcelData['to_date']  = $to_date_new->format('Y-m-d');
                        } else {
                            $uploadExcelData['to_date'] = null;
                        }

                        $uploadExcelData['item_code'] = $val['item_code'];
                        $uploadExcelData['item_description'] = $val['item_description'];
                        $uploadExcelData['part_number'] = $val['part_number'];
                        $uploadExcelData['uom'] = $val['uom'];
                        $uploadExcelData['delivery_lead_time'] = $val['lead_time_days'];
                        $uploadExcelData['unit_price'] = $val['unit_price'];
                        $uploadExcelData['currency_id'] = $currency['id'];
                        $uploadExcelData['is_active'] = 1;
                        $uploadExcelData['created_by'] = $user->id;
                        $uploadExcelData['tenant_id'] = $tenantId;  
                        $successCount = $successCount + 1;
                        array_push($upload_arr,$uploadExcelData); 
                    }
                    
                }
                
                if (count($itemCodeRequired) === 0) { 
                    DB::commit();
                    PriceList::insert($upload_arr);
                 
                }
            } catch (\Exception $exception) {
                DB::rollBack();
                $message = $exception->getMessage();
                Log::error($message);
                return false;
            }
        }
        if ($duplicates) {
            foreach ($duplicates as $key => $dupl) {
                $data['success'] = 2;  // skipped 
                $data['msg'] = 'Line no ' . ($key + 1) . ' item code ' . $dupl->item_code . ' skipped';
                array_push($skipRecords, $data);
            }
        }

        if (count($itemCodeRequired) > 0) {
            return ['success' => false, 'message' => 'Required Fields', 'data' => $itemCodeRequired];
        }

        if ($successCount > 0) {
            $data['success'] = 1; // Item Uploaded
            $data['msg'] =  $successCount . ' Item(s) uploaded successfully out of ' . count($excelUploadData);
            array_push($skipRecords, $data);
        }
        return ['success' => true, 'message' => 'Excel uploaded successfully', 'data' => $skipRecords];
    }
    public function storePriceList(Request $request)
    {
        DB::beginTransaction();

        $input = $request->all();


        $user = $request->user();
        $dataToUpload = array_unique(array_column($input['items'], 'item_code'));
        $excelUploadDataN = (array_intersect_key($input['items'], $dataToUpload));
        $diff = array_diff(array_map('json_encode', $input['items']), array_map('json_encode', $excelUploadDataN));
        $duplicates = array_map('json_decode', $diff);
        if ($duplicates) {
            foreach ($duplicates as $key => $val2) {
                return ['success' => false, 'message' => 'Line no ' . ($key + 1) . ' item code ' . $val2->item_code . ' duplicated'];
            }
        }

        try {
            if ($input['items']) {
                $x = 0;
                foreach ($input['items'] as $val) {
                    if (!isset($val['item_code']) || $val['item_code'] == null) {
                        return ['success' => false, 'message' => 'Line no ' . ($x + 1) . ' item code required'];
                    }
                    if (!isset($val['item_description']) || $val['item_description'] == null) {
                        return ['success' => false, 'message' => 'Line no ' . ($x + 1) . ' item description required'];
                    }
                    if (!isset($val['uom']) || $val['uom'] == null) {
                        return ['success' => false, 'message' => 'Line no ' . ($x + 1) . ' UOM required'];
                    }
                    if (!isset($val['currency']) || $val['currency'] == null) {
                        return ['success' => false, 'message' => 'Line no ' . ($x + 1) . ' currency required'];
                    }
                    if (!isset($val['unit_price']) || $val['unit_price'] == null) {
                        return ['success' => false, 'message' => 'Line no ' . ($x + 1) . ' unit price required'];
                    }
                    if (isset($val['from_date']) && isset($val['to_date'])) {
                        $fromDateVal =   Carbon::parse($val['from_date'])->format('Y-m-d');
                        $toDateVal =   Carbon::parse($val['to_date'])->format('Y-m-d');
                        if ($fromDateVal > $toDateVal) {
                            return ['success' => false, 'message' => 'Line no ' . ($x + 1) . ' To date is less than from date'];
                        }
                    }
                    $x++;
                    $priceList = PriceList::select('id', 'item_code')
                        ->whereRaw("LOWER(item_code) = LOWER('{$val['item_code']}')")
                        ->where('tenant_id', $input['tenantId'])
                        ->where('created_by', $user->id)
                        ->whereNotNull('id')
                        ->first();

                    if (!empty($priceList)) {
                        return ['success' => false, 'message' => 'Item code ' . $val['item_code'] . ' already exists'];
                    }
                    if (isset($val['from_date'])) {
                        if ($val['from_date']) {
                            $uploadExcelData['from_date'] =  Carbon::parse($val['from_date'])->format('Y-m-d');
                        }
                    }
                    if (isset($val['to_date'])) {
                        if ($val['to_date']) {
                            $uploadExcelData['to_date'] = Carbon::parse($val['to_date'])->format('Y-m-d');
                        }
                    }

                    $uploadExcelData['item_code'] = $val['item_code'];
                    $uploadExcelData['item_description'] = $val['item_description'];
                    $uploadExcelData['part_number'] = isset($val['part_number']) ? $val['part_number'] : null;
                    $uploadExcelData['uom'] = $val['uom'];
                    $uploadExcelData['delivery_lead_time'] = isset($val['delivery_lead_time']) ? $val['delivery_lead_time'] : null;
                    $uploadExcelData['unit_price'] = isset($val['unit_price']) ? $val['unit_price'] : null;
                    $uploadExcelData['currency_id'] = $val['currency'];
                    $uploadExcelData['is_active'] = (isset($val['active']) && $val['active'] == true) ? 1 : 0;
                    $uploadExcelData['created_by'] = $user->id;
                    $uploadExcelData['tenant_id'] =  $input['tenantId'];
                    PriceList::create($uploadExcelData);
                }
            }
            DB::commit();
            return ['success' => true, 'message' => 'Successfully saved'];
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
    public function getFormData(Request $request)
    {
        $currency = CurrencyMaster::select(DB::raw("id as value,concat(currency_name,' (',currency_code,')') as label"))
            ->get();
        return [
            'currency' => $currency
        ];
    }
    public function getPriceList(Request $request)
    {
        $input = $request->all();
        $user = Auth::user();
        $search = $request->input('search.value');

        $query = PriceList::with(['currency'])
            ->where('tenant_id', $input['tenantId'])
            ->where('created_by', $user->id)
            ->orderBy('id', 'desc');

        if ($search) {
            $search = str_replace("\\", "\\\\", $search);
            $query = $query->where(function ($query) use ($search) {
                $query->orwhereRaw("LOWER(item_code) LIKE LOWER('%{$search}%')");
                $query->orwhereRaw("LOWER(item_description) LIKE LOWER('%{$search}%')");
                $query->orwhereRaw("LOWER(part_number) LIKE LOWER('%{$search}%')");
                $query->orwhereRaw("LOWER(uom) LIKE LOWER('%{$search}%')");
            });
        }
        return DataTables::eloquent($query)
            ->addColumn('Actions', 'Actions', "Actions")
            ->order(function ($query) use ($input) {
                if (request()->has('order')) {
                    if ($input['order'][0]['column'] == 0) {
                        $query->orderBy('id', $input['order'][0]['dir']);
                    }
                }
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function editDataPriceList(Request $request)
    {
        $input = $request->all();
        return PriceList::where('id', $input['id'])->first();
    }
    public function updatePriceList(Request $request)
    {
        $input = $request->all();
        $user = $request->user();

        if (!isset($input['item_code']) || $input['item_code'] == null) {
            return ['success' => false, 'message' => 'Item code is required'];
        }
        if (!isset($input['item_description']) || $input['item_description'] == null) {
            return ['success' => false, 'message' => 'Item description is required'];
        }
        if (!isset($input['uom']) || $input['uom'] == null) {
            return ['success' => false, 'message' => 'UOM is required'];
        }
        if (!isset($input['currency_id']) || $input['currency_id'] == null) {
            return ['success' => false, 'message' => 'Currency is required'];
        }
        if (!isset($input['unit_price']) || $input['unit_price'] == null) {
            return ['success' => false, 'message' => 'Unit price is required'];
        }
        if (isset($input['from_date']) && isset($input['to_date'])) {
            $fromDateVal =   Carbon::parse($input['from_date'])->format('Y-m-d');
            $toDateVal =   Carbon::parse($input['to_date'])->format('Y-m-d');
            if ($fromDateVal > $toDateVal) {
                return ['success' => false, 'message' => 'To date is less than from date'];
            }
        }
        $priceList = PriceList::select('id', 'item_code')
            ->whereRaw("LOWER(item_code) = LOWER('{$input['item_code']}')")
            ->where('id', '!=', $input['id'])
            ->where('tenant_id', $input['tenantId'])
            ->where('created_by', $user->id)
            ->whereNotNull('id')
            ->first();

        if (!empty($priceList)) {
            return ['success' => false, 'message' => 'Item code already exists'];
        }

        try {
            $uploadExcelData['item_code'] = $input['item_code'];
            $uploadExcelData['item_description'] = $input['item_description'];
            $uploadExcelData['part_number'] = isset($input['part_number']) ? $input['part_number'] : null;
            $uploadExcelData['uom'] = $input['uom'];
            $uploadExcelData['part_number'] = isset($input['part_number']) ? $input['part_number'] : null;
            $uploadExcelData['delivery_lead_time'] = isset($input['delivery_lead_time']) ? $input['delivery_lead_time'] : null;
            $uploadExcelData['unit_price'] = isset($input['unit_price']) ? $input['unit_price'] : null;
            $uploadExcelData['currency_id'] = $input['currency_id'];
            $uploadExcelData['from_date']  = isset($input['from_date']) ? Carbon::parse($input['from_date'])->format('Y-m-d') : null;
            $uploadExcelData['to_date'] = isset($input['to_date']) ?  Carbon::parse($input['to_date'])->format('Y-m-d') : null;
            $uploadExcelData['is_active'] = (isset($input['is_active']) && ($input['is_active'] == true || $input['is_active'] == 1)) ? 1 : 0;
            $uploadExcelData['updated_by'] = $user->id;
            $uploadExcelData['tenant_id'] =  $input['tenantId'];

            PriceList::where('id', $input['id'])->update($uploadExcelData);
            DB::commit();
            return ['success' => true, 'message' => 'Successfully updated'];
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
}
