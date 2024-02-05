<?php

/**
 * @author Lahiru Dilshan
 * @date 2021-8-15
 */

namespace App\Http\Controllers\Supplier\API;

use App\Http\Controllers\Controller;
use App\Models\FormData;
use App\Models\SupplierDetail;
use App\Models\Tenant;
use App\Models\UserTenant;
use App\Services\DynamicFormService;
use App\Services\FileService;
use App\Services\Shared\SharedService;
use App\Services\Shared\TenantService;
use App\Services\Supplier\SupplierService;
use App\Services\User\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;
use Illuminate\Support\Facades\Storage;

class SupplierRegistrationController extends Controller
{
    private $dynamicFormService;
    private $supplierService;
    private $userService;
    private $fileService;
    private $tenantService;
    private $sharedService;
    public function __construct(
        DynamicFormService $dynamicFormService,
        SupplierService $supplierService,
        FileService $fileService,
        UserService $userService,
        TenantService $tenantService,
        SharedService $sharedService
    ) {
        $this->dynamicFormService = $dynamicFormService;
        $this->supplierService = $supplierService;
        $this->fileService = $fileService;
        $this->userService = $userService;
        $this->tenantService = $tenantService;
        $this->sharedService = $sharedService;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $tenantId = $request->input('tenantId') !== 'null' ? $request->input('tenantId') : null;
            $apiKey = $request->input('apiKey') !== 'null' ? $request->input('apiKey') : null;
            $tenant = null;
            $uuid = null;
            if ((!$tenantId || $tenantId === 'null') && $apiKey) {
                $tenant = $this->tenantService->getTenantByAPIKey($apiKey);
                throw_unless($tenant, 'Invalid API Key passed');
            }

            if ($tenantId || $tenant->id) {
                $tenantMasterId = (isset($tenantId) && $tenantId != null) ? $tenantId : $tenant->id;
                $api = $this->tenantService->getTenant($tenantMasterId);
                $apiKey = $api->api_key;
            }


            if ($user) {
                $userTenant = UserTenant::select('uuid')
                    ->where([
                        ['user_id', $user->id],
                        ['tenant_id', $tenantId]
                    ])
                    ->first();

                if (!empty($userTenant)) {
                    $uuid = $userTenant->uuid;
                }
            }

            $ERPFormData = $this->sharedService->fetch([
                'url' => env('ERP_ENDPOINT'),
                'method' => 'POST',
                'data' => [
                    'api_key'       => $apiKey,
                    'request'       => 'GET_ERP_FORM_DATA',
                    'supplier_uuid' => $uuid ?? null,
                ]
            ]);
            $data = $this->dynamicFormService->getFormData($tenantId ?? $tenant->id, $user->id);
            $userTenant = $this->tenantService->getUserTenant($request->user(), $tenantId ?? $tenant->id);
            $currencyMaster = $ERPFormData->data->currencyMaster;
            $country = $ERPFormData->data->countryMaster;
            $supplierCategory = $ERPFormData->data->supplierCategoryMaster;
            $supplierSubCategory = $ERPFormData->data->supplierCategorySubMaster;

            $supplierDetails = $this->getSupplierDetailByFieldId($user->id,$tenantId ?? $tenant->id);
            $userEmail = $supplierDetails->where('form_field_id', 69)->pluck('value')->first();
            $name = $supplierDetails->where('form_field_id', 4)->pluck('value')->first();


            $optionArrSub = [];
            $optionArrCat = [];
            $optionArrCurrency = [];
            $optionArCountry = [];
            $optionArrSubAll = [];
            $data = $data->toArray();
            $data[0]['groups'][0]['controls'][0]['field']['options'] = [];
            $data[0]['groups'][0]['controls'][1]['field']['options'] = [];
            $data[0]['groups'][0]['controls'][1]['alloptions'] = [];
            $data[2]['groups'][0]['controls'][0]['field']['options'] = [];
            $data[4]['groups'][0]['controls'][2]['field']['options'] = [];

            foreach ($supplierCategory as $supplierCategoryVal) {
                $optionArrCat = ['form_data_id' => $supplierCategoryVal->supCategoryMasterID, 'form_field_id' => 1, 'id' => $supplierCategoryVal->supCategoryMasterID, 'status' => 1, 'option' => [
                    'id' => $supplierCategoryVal->supCategoryMasterID, 'status' => 1, 'text' => $supplierCategoryVal->categoryName, 'value' => $supplierCategoryVal->supCategoryMasterID
                ]];
                array_push($data[0]['groups'][0]['controls'][0]['field']['options'], $optionArrCat);
            }

            foreach ($supplierSubCategory as $supplierCategorySubVal) {
                $optionArrSubAll = [
                    'form_data_id' => $supplierCategorySubVal->supCategorySubID, 'form_field_id' => 2, 'id' => $supplierCategorySubVal->supCategorySubID, 'status' => 1,
                    'categoryId' => $supplierCategorySubVal->supMasterCategoryID,
                    'option' => ['id' => $supplierCategorySubVal->supCategorySubID, 'status' => 1, 'text' => $supplierCategorySubVal->categoryName, 'value' => $supplierCategorySubVal->supCategorySubID],

                    'id' => $supplierCategorySubVal->supCategorySubID, 'status' => 1, 'text' => $supplierCategorySubVal->categoryName, 'value' => $supplierCategorySubVal->supCategorySubID,
                    'categoryId' => $supplierCategorySubVal->supMasterCategoryID,
                ];

                $optionArrSub = [
                    'form_data_id' => $supplierCategorySubVal->supCategorySubID, 'form_field_id' => 2, 'id' => $supplierCategorySubVal->supCategorySubID, 'status' => 1,
                    'categoryId' => $supplierCategorySubVal->supMasterCategoryID,
                    'option' => ['id' => $supplierCategorySubVal->supCategorySubID, 'status' => 1, 'text' => $supplierCategorySubVal->categoryName, 'value' => $supplierCategorySubVal->supCategorySubID],
                ];

                array_push($data[0]['groups'][0]['controls'][1]['field']['options'], $optionArrSub);
                array_push($data[0]['groups'][0]['controls'][1]['alloptions'], $optionArrSubAll);
            }

            foreach ($currencyMaster as $currencyMasterVal) {
                $optionArrCurrency = ['form_data_id' => $currencyMasterVal->currencyID, 'form_field_id' => 28, 'id' => $currencyMasterVal->currencyID, 'status' => 1, 'option' => [
                    'id' => $currencyMasterVal->currencyID, 'status' => 1, 'text' => $currencyMasterVal->CurrencyName . ' (' . $currencyMasterVal->CurrencyCode . ')', 'value' => $currencyMasterVal->currencyID
                ]];
                array_push($data[2]['groups'][0]['controls'][0]['field']['options'], $optionArrCurrency);
            }

            foreach ($country as $countryVal) {
                $optionArCountry = ['form_data_id' => $countryVal->countryID, 'form_field_id' => 46, 'id' => $countryVal->countryID, 'status' => 1, 'option' => [
                    'id' => $countryVal->countryID, 'status' => 1, 'text' => $countryVal->countryName, 'value' => $countryVal->countryID
                ]];
                array_push($data[4]['groups'][0]['controls'][2]['field']['options'], $optionArCountry);
            }

            $supplierDetailCount = 0;
            if ($tenantId) {
                $supplierDetailCount = SupplierDetail::where('tenant_id', $tenantId)
                    ->where('user_id', $request->user()->id)
                    ->count();
            }

            $userTeneantCount = UserTenant::select('tenant_id')->where('user_id', $request->user()->id)
                ->whereIn('kyc_status', [1, 2, 3]);
            $tenantId = $userTeneantCount->get();
            $tenantList = Tenant::select(DB::raw("id as value,name as label"))->whereIn('id', $userTeneantCount)->get();
            $checkCompanyRegistration = $this->checkSupplierAttachedCompanyRegistrationCertificate($user,(int) $request->input('tenantId'));
            $checkLocalSupplier = $this->checkIsLocalSupplier($user, (int) $request->input('tenantId'));
            $checkLocalSupplierHasAttachment = $this->checkLocalSupplierHasAttachment($user, (int) $request->input('tenantId'));

            $dataRec = [];
            foreach ($data as $val) {
                if (isset($val['id'])) {
                    array_push($dataRec, $val);
                }
            }

            return response()->json([
                'success'   => true,
                'message'   => 'Data received',
                'data'      => [
                    'formData'      => $dataRec,
                    'userTenant'    => $userTenant,
                    'supplierDetailCount' => $supplierDetailCount,
                    'userTeneantCount' => $userTeneantCount->count(),
                    'tenantList' => $tenantList,
                    'userId' => $request->user()->id,
                    'checkCompanyRegistration' => $checkCompanyRegistration,
                    'checkLocalSupplier' => $checkLocalSupplier,
                    'checkLocalSupplierHasAttachment' => $checkLocalSupplierHasAttachment,
                    'userEmail' => $userEmail,
                    'name' => $name,

                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => 'Something went wrong!',
                'data'      => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(Request $request)
    {
        try {
            $isSuperAdmin = $this->userService->isSuperAdmin($request->user());
            throw_if($isSuperAdmin, 'Super Admin cannot create Supplier KYC');

            $data['formData']   = $request->input('formData');
            $data['current_section']   = $request->input('current_section_index');
            $data['tenantId']   = $request->input('tenantId');
            $data['userId']     = $request->user()->id;

            throw_unless($data['formData'], 'Form data not found in Request');
            throw_unless($data['tenantId'], 'Tenant Id must pass to save supplier details');
            throw_unless($data['userId'], 'User not authenticated');

            $data = $this->supplierService->handleSupplierFormData($data);

            return response()->json([
                'success'   => true,
                'message'   => 'Supplier Details saved',
                'data'      => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage(),
                'data'      => $e
            ], 500);
        }
    }

    /**
     * show supplier KYC saved data
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request)
    {
        try {
            $user = $request->user();

            $data = $this->supplierService->getSupplierDetails($user, $request->input('tenantId'));

            if ($request->input('tenantId')) {
                $api = $this->tenantService->getTenant($request->input('tenantId'));
                $apiKey = $api->api_key;
            }

            $ERPFormData = $this->sharedService->fetch([
                'url' => env('ERP_ENDPOINT'),
                'method' => 'POST',
                'data' => [
                    'api_key'       => $apiKey,
                    'request'       => 'GET_ERP_FORM_DATA'
                ]
            ]);

            $currencyMaster = $ERPFormData->data->currencyMaster;
            $country = $ERPFormData->data->countryMaster;
            $supplierCategory = $ERPFormData->data->supplierCategoryMaster;
            $supplierSubCategory = $ERPFormData->data->supplierCategorySubMaster;

            foreach ($data as $key1 => $data1) {
                foreach ($data1['groups'] as $val2) {
                    foreach ($val2['controls'] as $val3) {
                        foreach ($val3['field']['values'] as $key1 => $val4) {
                            if ($val3['form_field_id'] == 1) { //Category
                                $category = collect($supplierCategory)->where('supCategoryMasterID', $val4['value'])->first();
                                $val4['value'] = $category->categoryName;
                            } else if ($val3['form_field_id'] == 2) { // Sub Category
                                $subCategory = collect($supplierSubCategory)->where('supCategorySubID', $val4['value'])->first();
                                $val4['value'] = $subCategory->categoryName;
                            } else if ($val3['form_field_id'] == 28) { // Preferred Functional Currency
                                $currency = collect($currencyMaster)->where('currencyID', $val4['value'])->first();
                                $val4['value'] = $currency->CurrencyName . ' (' . $currency->CurrencyCode . ')';
                            } else if ($val3['form_field_id'] == 46) { // Country
                                $countryMaster = collect($country)->where('countryID', $val4['value'])->first();
                                $val4['value'] = $countryMaster->countryName;
                            } else if ($val4['form_data_id'] > 0) {
                                $value = FormData::where('id', $val4['form_data_id'])->first();
                                $val4['value'] = $value['text'];
                            }
                        }
                    }
                }
            }

            return response()->json([
                'success'   => true,
                'message'   => 'Data received',
                'data'      => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => 'Something went wrong!',
                'data'      => $e->getMessage()
            ], 500);
        }
    }

    /**
     * update supplier KYC status
     * @param Request $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function updateSupplierKYCFormStatus(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $status = $request->input('status');

                $tenantId = $request->input('tenantId') !== 'null' ? $request->input('tenantId') : null;
                $apiKey = $request->input('apiKey') !== 'null' ? $request->input('apiKey') : null;
                $tenant = null;

                $supplierId = $request->input('supplierId') !== 'null' ? $request->input('supplierId') : null;
                $user = $request->user();

                if ((!$tenantId || $tenantId === 'null') && $apiKey) {
                    $tenant = $this->tenantService->getTenantByAPIKey($apiKey);
                    throw_unless($tenant, 'Invalid API Key passed');
                }

                // throw_unless($status, 'Status must be provide to update KYC form status');
                throw_unless(($tenantId ?? $tenant->id), 'Tenant ID must be passed for updating KYC status');
                throw_unless($supplierId ?? $user->id, 'Something went wrong, logged user not found');

                $isUpdated = UserTenant::where('user_id', $supplierId ?? $user->id)
                    ->where('tenant_id', $tenantId ?? $tenant->id)
                    ->update([
                        'kyc_status' => $status
                    ]);

                throw_unless($isUpdated, "Something went wrong!, KYC status couldn't be updated");

                return response()->json([
                    'success'   => true,
                    'message'   => 'KYC Form has been submitted for approval!',
                    'data'      => $isUpdated
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage(),
                'data'      => $e
            ], 500);
        }
    }

    /**
     * upload form file
     * @param Request $request
     * @return JsonResponse|void
     */
    public function uploadHandler(Request $request)
    {
        try {
            $attachment = $request->input('attachment');

            if (!empty($attachment) && isset($attachment['dataURL'])) {
                $extension = $attachment['type'];
                $url = $request->input('path') . '/' . $request->input('attachment.name');
                $urlPath = $request->input('path') . '/' . $request->input('attachment.name');

                $allowExtensions = ['png', 'jpg', 'jpeg', 'pdf', 'doc', 'docm', 'docx'];

                throw_unless(in_array($extension, $allowExtensions), 'Invalid file passed');
                throw_if($attachment['size'] && $attachment['size'] > 10485760, 'Maximum allowed file size is 10 MB. Please upload lesser than 10 MB.');
                throw_unless($request->input('path'), 'Path must be passed');

                $decodeFile = base64_decode($attachment['dataURL']);

                $url = $this->fileService->uploadFile([
                    'url'           => $url,
                    'attachment'    => $decodeFile
                ]);

                throw_unless($url, 'Something went wrong when file uploading.');

                return response()->json([
                    'success'   => true,
                    'message'   => 'File has been successfully uploaded',
                    'data'      => [
                        'url' => $urlPath
                    ]
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage(),
                'data'      => $e
            ], 500);
        }
    }

    /**
     * handle download
     * @param Request $request
     * @return StreamedResponse
     * @throws Throwable
     */
    public function downloadAttachment(Request $request)
    {

        $input = $request->all();
        $path = $input['attachment'];
        if (Storage::disk('s3')->exists($path)) {
            return Storage::disk('s3')->download($path, 'Attachment');
        } else {
            return $this->sendError('Attachments not found', 500);
        }
    }
    public function getApiKey(Request $request)
    {
        $input = $request->all();
        return $this->tenantService->getTenant($input['tenantId']);
        //$request->all();
    }
    public function insertTenantData(Request $request)
    {

        $input = $request->all();
        $tenantId = $input['tenant_id'];
        $userId = $input['user_id'];
        $selectedTenantId = $input['selectedTenantId'];
        try {
            throw_unless($userId, 'User id not found in Request');
            throw_unless($tenantId, 'Tenant id not found in Request');
            if ($selectedTenantId) {
                $selectedTenantId = $input['selectedTenantId'];
            } else {
                $kycCompletedTenantId =  UserTenant::where('user_id', $request->user()->id)
                    ->whereIn('kyc_status', [1, 2, 3])
                    ->first();
                $selectedTenantId =  $kycCompletedTenantId['tenant_id'];
            }
            $supplierDetail = SupplierDetail::with(['field'])
                ->where('user_id', $userId)
                ->where('tenant_id', $selectedTenantId)
                ->whereHas('field', function ($q) {
                    $q->where('data_from_tenant', '!=', 1);
                })
                ->get();
            if (!empty($supplierDetail)) {
                foreach ($supplierDetail as $val) {
                    $data = [
                        'user_id' => $val['user_id'],
                        'tenant_id' => $tenantId,
                        'form_section_id' => $val['form_section_id'],
                        'form_group_id' => $val['form_group_id'],
                        'form_field_id' => $val['form_field_id'],
                        'form_data_id' => $val['form_data_id'],
                        'sort' => $val['sort'],
                        'value' => $val['value'],
                        'status' => $val['status']
                    ];
                    $isUpdated  = SupplierDetail::insert($data);
                }
            }
            throw_unless($isUpdated, "Something went wrong!, KYC status couldn't be updated");
            return response()->json([
                'success'   => true,
                'message'   => 'Supplier Details saved',
                'data'      => $selectedTenantId
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage(),
                'data'      => $e
            ], 500);
        }
    }

    public function downloadExcelTemplateDownload()
    {
        if (Storage::disk('s3')->exists('price_list_template/price_list_template.xlsx')) {
            return Storage::disk('s3')->download('price_list_template/price_list_template.xlsx', 'Attachment');
        } else {
            return $this->sendError('Attachments not found', 500);
        }
    }

    private function checkSupplierAttachedCompanyRegistrationCertificate(object $user, int $tenantId)
    {
        $sortId = SupplierDetail::select('sort')->where('user_id', $user->id)
            ->where('tenant_id', $tenantId)
            ->where('value', 'Company Registration Certificate')
            ->first();

        if ($sortId) {
            $checkAttachmentIsUploaded = SupplierDetail::select('value')
                ->where('user_id', $user->id)
                ->where('tenant_id', $tenantId)
                ->where('form_field_id', 12)
                ->where('sort', $sortId->sort)
                ->first();

            if ($checkAttachmentIsUploaded) {
               return true;
            } else {
                return false;
            }
        }
    }

    private function checkIsLocalSupplier(object $user, int $tenantId)
    {
        $localSupplier = SupplierDetail::select('sort')->where('user_id', $user->id)
            ->where('tenant_id', $tenantId)
            ->where('form_field_id', 66)
            ->count();

        if ($localSupplier > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function checkLocalSupplierHasAttachment(object $user, int $tenantId)
    {
        $localSupplier = SupplierDetail::select('sort')->where('user_id', $user->id)
            ->where('tenant_id', $tenantId)
            ->where('form_field_id', 66)
            ->count();

        if ($localSupplier > 0) {
            $sortId = SupplierDetail::select('sort')->where('user_id', $user->id)
                ->where('tenant_id', $tenantId)
                ->where('value', 'Omanisation')
                ->first();

            if ($sortId) {
                $checkAttachmentIsUploaded = SupplierDetail::select('value')
                    ->where('user_id', $user->id)
                    ->where('tenant_id', $tenantId)
                    ->where('form_field_id', 12)
                    ->where('sort', $sortId->sort)
                    ->first();

                if ($checkAttachmentIsUploaded) {
                    return true;
                } else {
                    return false;
                }
        } else {
            return false;
        }
    } else {
            return false;
        }
    }

    public function  getSupplierDetailByFieldId($userId,$tenantId)
    {
        return SupplierDetail::select('value','form_field_id')
            ->where('user_id', $userId)
            ->where('tenant_id', $tenantId)
            ->whereIn('form_field_id', [69,4])
            ->get();
    }
}
