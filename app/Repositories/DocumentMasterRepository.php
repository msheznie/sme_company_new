<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\ContractMaster;
use App\Models\ContractMilestoneRetention;
use App\Models\DocumentMaster;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

/**
 * Class DocumentMasterRepository
 * @package App\Repositories
 * @version May 6, 2024, 4:50 pm +04
*/

class DocumentMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'documentType',
        'description',
        'status',
        'companySystemID',
        'created_by',
        'updated_by'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DocumentMaster::class;
    }

    public function getDocumentMasterData(Request $request){

        $input = $request->all();
        $companySystemID = $input['companyId'];

        $languages =  $this->model->documentMaster($companySystemID);
        return DataTables::eloquent($languages)
            ->addColumn('Actions', 'Actions', "Actions")
            ->addIndexColumn()
            ->make(true);
    }

    public function createDocumentMaster($input) {

        $companySystemID = $input['companySystemID'];
        $documentType = $input['documentType'];

        $existingDocument = DocumentMaster::where('documentType', $documentType)
            ->where('companySystemID', $companySystemID)
            ->first();

        if ($existingDocument) {
            return [
                'status' => false,
                'message' => trans('common.document_type_must_be_unique')
            ];
        }

        try{
            DB::beginTransaction();
            $data = [
                'uuid' => bin2hex(random_bytes(16)),
                'documentType' => $documentType,
                'description' => isset($input['description']) ? $input['description'] : null,
                'status' => $input['status'],
                'companySystemID' => $companySystemID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];

            DocumentMaster::create($data);

            DB::commit();
            return ['status' => true, 'message' => trans('common.document_master_created_successfully')];
        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage(), 'line' => __LINE__];
        }
    }

    public function documentStatusUpdate(Request $request){
        $input = $request->all();
        $documentUuid = $input['uuid'];
        $status = $input['status'];

        DB::beginTransaction();
        try{

            $data = [
                'status' => $status,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];

            DocumentMaster::where('uuid', $documentUuid)->update($data);

            DB::commit();
            return ['status' => true, 'message' => trans('common.document_status_updated_successfully')];

        } catch (\Exception $ex){
            DB::rollBack();
            return ['status' => false, 'message' => $ex->getMessage()];
        }
    }


}
