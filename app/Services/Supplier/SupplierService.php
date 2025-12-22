<?php

namespace App\Services\Supplier;

use App\helper\GeneralHelper;
use App\Models\FormData;
use App\Models\FormSection;
use App\Models\SupplierDetail;
use App\Models\SupplierDetailHistory;
use App\Models\SupplierMasterHistory;
use App\Models\User;
use App\Models\UserTenant;
use App\Services\GeneralService;
use App\Services\Shared\TenantService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Throwable;
use App\Services\Shared\SharedService;
use Yajra\DataTables\DataTables;
define('SINGLE', 'single');
define('MULTIPLE', 'multiple');

// supplier status
define('PENDING', 0);
define('SUBMITTED', 1);
define('PENDING_FOR_APPROVAL', 2);
define('APPROVED', 3);
define('REJECT', 4);

class SupplierService
{
    private $records = [];
    private $userId = null;
    private $tenantId = null;
    private $tenantService = null;
    private $sharedService;
    public function __construct(TenantService $tenantService,
                                SharedService $sharedService)
    {
        $this->tenantService = $tenantService;
        $this->sharedService = $sharedService;
    }

    /**
     * get form generate details
     * @throws Throwable
     */
    public function handleSupplierFormData($data)
    {
        return DB::transaction(function () use ($data) {
            $sort = 1;

            $this->userId = $data['userId'];
            $this->tenantId = $data['tenantId'];

            // indexes
            $type = 0;
            $id = 2;

            // loop sections
            foreach ($data['formData'] as $sectionKey => $section) {
                $formSection = $this->extractDataFromString($sectionKey);

                throw_unless($formSection, 'Invalid Form Section Key passed');

                // clean up previous section records before insert new section
                $this->cleanSupplierSectionDetails($formSection[$id], $data['tenantId'], $data['userId']);

                // loop section's groups
                foreach ($section as $groupKey => $groups) {
                    $formGroup = $this->extractDataFromString($groupKey);

                    throw_unless($formGroup, 'Invalid Form Group Key passed');

                    // loop group's section
                    foreach ($groups as $controlKey => $control) {
                        if (!empty($control)) {
                            // handle multiple controls groups e.g: multiple attachments
                            if (isset($formGroup[$type]) && $formGroup[$type] === MULTIPLE) {
                                foreach ($control as $subControlKey => $subControl) {
                                    // extract data from string key like text__control__1
                                    $formControl = $this->extractDataFromString($subControlKey);

                                    // make array and push data to global array for inserting DB
                                    $this->makeDBRecord([
                                        'type'              => $formControl[$type],
                                        'control'           => $subControl,
                                        'form_section_id'   => $formSection[$id],
                                        'form_group_id'     => $formGroup[$id],
                                        'form_field_id'     => $formControl[$id],
                                        'sort'              => $sort
                                    ]);
                                }
                            } else {
                                // extract data from string key like text__control__1
                                $formControl = $this->extractDataFromString($controlKey);

                                // make array and push data to global array for inserting DB
                                $this->makeDBRecord([
                                    'type'              => $formControl[$type],
                                    'control'           => $control,
                                    'form_section_id'   => $formSection[$id],
                                    'form_group_id'     => $formGroup[$id],
                                    'form_field_id'     => $formControl[$id],
                                    'sort'              => $sort
                                ]);
                            }

                            $sort++;
                        }
                    }
                }
            }

            // update current completed section index
            $this->tenantService->updateUserTenant(
                ['id' => $data['userId']],
                $this->tenantId,
                ['current_section' => $data['current_section']]
            );

            return $this->saveSupplierDetails();
        });
    }

    public function updateUserTenantCurrentSection($section)
    {
    }

    /**
     * clean supplier section record before insert updated records
     * @param int $sectionId
     * @param int $tenantId
     * @param int $userId
     * @return void
     */
    private function cleanSupplierSectionDetails(int $sectionId, int $tenantId, int $userId): void
    {
        SupplierDetail::where('form_section_id', $sectionId)
            ->where('tenant_id', $tenantId)
            ->where('user_id', $userId)
            ->delete();
    }

    /**
     * add record to array for insert DB
     * @param $params
     * @return void
     */
    private function makeDBRecord($params)
    {
        switch ($params['type']) {
            case 'text':
                $this->handleText($params);
                break;
            case 'textarea':
                $this->handleTextarea($params);
                break;
            case 'select':
                $this->handleSelect($params);
                break;
            case 'multi_select':
                $this->handleMultiSelect($params);
                break;
            case 'date':
                $this->handleDate($params);
                break;
            case 'checkbox':
                $this->handleCheckbox($params);
                break;
            case 'file_uploader':
                $this->handleFileUploader($params);
                break;
            default:
                break;
        }
    }

    /**
     * handle text input data
     * @param $params
     * @return void
     */
    private function handleText($params)
    {
        if ($params['control'] !== null) {
            array_push($this->records, [
                'user_id' => $this->userId,
                'tenant_id' => $this->tenantId,
                'form_section_id' => $params['form_section_id'],
                'form_group_id' => $params['form_group_id'],
                'form_field_id' => $params['form_field_id'],
                'form_data_id' => null,
                'sort' => $params['sort'],
                'value' => $params['control'],
                'created_at' => Carbon::now()
            ]);
        }
    }

    /**
     * handle textarea input data
     * @param $params
     * @return void
     */
    private function handleTextarea($params)
    {
        if ($params['control'] !== null) {
            array_push($this->records, [
                'user_id' => $this->userId,
                'tenant_id' => $this->tenantId,
                'form_section_id' => $params['form_section_id'],
                'form_group_id' => $params['form_group_id'],
                'form_field_id' => $params['form_field_id'],
                'form_data_id' => null,
                'sort' => $params['sort'],
                'value' => $params['control'],
                'created_at' => Carbon::now()
            ]);
        }
    }

    /**
     * handle select input data
     * @param $params
     * @return void
     */
    private function handleSelect($params)
    {
        $control = $this->jsonDecode($params['control']);

        if ($control && $control->value !== null) {
            array_push($this->records, [
                'user_id' => $this->userId,
                'tenant_id' => $this->tenantId,
                'form_section_id' => $params['form_section_id'],
                'form_group_id' => $params['form_group_id'],
                'form_field_id' => $params['form_field_id'],
                'form_data_id' => $control->id ?? null,
                'sort' => $params['sort'],
                'value' => $control->value,
                'created_at' => Carbon::now()
            ]);
        }
    }

    /**
     * handle multi select input data
     * @param $params
     * @return void
     */
    private function handleMultiSelect($params)
    {
        foreach ($params['control'] as $controlJson) {
            $control = $this->jsonDecode($controlJson);

            if ($control && $control->value !== null) {
                array_push($this->records, [
                    'user_id' => $this->userId,
                    'tenant_id' => $this->tenantId,
                    'form_section_id' => $params['form_section_id'],
                    'form_group_id' => $params['form_group_id'],
                    'form_field_id' => $params['form_field_id'],
                    'form_data_id' => $control->id ?? null,
                    'sort' => $params['sort'],
                    'value' => $control->value,
                    'created_at' => Carbon::now()
                ]);
            }
        }
    }

    /**
     * handle checkbox input data
     * @param $params
     * @return void
     */
    private function handleCheckbox($params)
    {
        if ($params['control'] !== null) {
            array_push($this->records, [
                'user_id'          => $this->userId,
                'tenant_id'        => $this->tenantId,
                'form_section_id'  => $params['form_section_id'],
                'form_group_id'    => $params['form_group_id'],
                'form_field_id'    => $params['form_field_id'],
                'form_data_id'     => null,
                'sort'             => $params['sort'],
                'value'            => $params['control'],
                'created_at'       => Carbon::now()
            ]);
        }
    }

    /**
     * handle file uploader input data
     * @param $params
     * @return void
     */
    private function handleFileUploader($params)
    {
        if ($params['control'] !== null) {
            array_push($this->records, [
                'user_id' => $this->userId,
                'tenant_id' => $this->tenantId,
                'form_section_id' => $params['form_section_id'],
                'form_group_id' => $params['form_group_id'],
                'form_field_id' => $params['form_field_id'],
                'form_data_id' => null,
                'sort' => $params['sort'],
                'value' => $params['control'],
                'created_at' => Carbon::now()
            ]);
        }
    }

    /**
     * handle date input data
     * @param $params
     * @return void
     */
    private function handleDate($params)
    {
        $value = $params['control'];

        if (is_array($params['control'])) {
            $value = Carbon::create($params['control']['year'], $params['control']['month'], $params['control']['day']);
        }

        if ($value !== null) {
            array_push($this->records, [
                'user_id' => $this->userId,
                'tenant_id' => $this->tenantId,
                'form_section_id' => $params['form_section_id'],
                'form_group_id' => $params['form_group_id'],
                'form_field_id' => $params['form_field_id'],
                'form_data_id' => null,
                'sort' => $params['sort'],
                'value' => $value,
                'created_at' => Carbon::now()
            ]);
        }
    }

    /**
     * @param $data
     * @param null $index
     * @param string $operator
     * @return false|mixed|string|string[]
     * @throws Throwable
     */
    private function extractDataFromString($data, $index = null, string $operator = '__')
    {
        throw_unless($data, 'Data must be provide for extract data');

        $array = explode($operator, $data);

        throw_unless($array, 'Something went wrong!');

        if ($index !== null) return $array[$index];

        return $array;
    }

    /**
     * save supplier
     * @return mixed
     */
    public function saveSupplierDetails()
    {
        // return $this->records;
        return SupplierDetail::insert($this->records);
    }

    /**
     * get supplier details
     * @param Object $user
     * @param integer $tenantId
     * @return Builder[]|Collection
     */
    public function getSupplierDetails(object $user, int $tenantId)
    {
        $supplierDetail = SupplierDetail::where('user_id', $user->id)
            ->where('tenant_id', $tenantId)
            ->where('status', true)
            ->count();

        $data = [];
        if($supplierDetail > 0){
            $templateMasterID = GeneralService::getTemplateMaster($tenantId);
            $data = FormSection::with(['groups.controls.field','groups.controls.field.options.option', 'groups.controls.field.values' => function($query) use($tenantId, $user) {
                return $query->where('user_id', $user->id)
                    ->where('tenant_id', $tenantId);
            }])
            ->where('template_master_id',$templateMasterID)
            ->where('status', true)
            ->orderBy('sort', 'ASC')
            ->get();

        }


        return $data;
    }

    public function jsonDecode($value)
    {
        return json_decode($value);
    }

    /**
     * @param string $uuid
     * @return Builder[]|Collection
     * @throws Throwable
     */
    public function getTenantSupplierDetailsByUUID(string $uuid)
    {
        $userTenant = UserTenant::with(['user'])->where('uuid', $uuid)->first();

        throw_unless($userTenant, 'Invalid UUID pass for fetching supplier data');
        throw_unless($userTenant->user, 'Something went wrong!, User not found in userTenant table');

        return $this->getSupplierDetails($userTenant->user, $userTenant->tenant_id);
    }

    /**
     * get user tenant by id
     * @param $userId
     * @param $tenantId
     * @return mixed
     * @throws Throwable
     */
    public function getUserTenantById($userId, $tenantId)
    {
        $userTenant = UserTenant::where('user_id', $userId)
            ->where('tenant_id', $tenantId)
            ->first();

        throw_unless($userTenant, 'User tenant not found');

        return $userTenant;
    }

    /**
     * @param $uuid
     * @param $status
     * @return mixed
     * @throws Throwable
     */
    public function updateSupplierKYCStatus($uuid, $status,$userEmail,$name)
    {
        throw_unless($uuid, 'UUID must be passed');
        throw_unless($status, 'status must be passed');


        if($status === 3){
            $userTenant = $this->getUserTenant($uuid);
            User::where('id',$userTenant->user->id)
                ->update([
                    'email' => $userEmail,
                    'name' => $name
                ]);
        }

        return UserTenant::where('uuid', $uuid)
            ->update([
                'kyc_status' => $status,
                'email' => $status == 3 ? $userEmail : DB::raw('email')
            ]);


    }
    public function getTenantSupplierDetailsCreationByUUID(string $uuid)
    {
        $userTenant = UserTenant::with(['user'])->where('uuid', $uuid)->first();

        throw_unless($userTenant, 'Invalid UUID pass for fetching supplier data');
        throw_unless($userTenant->user, 'Something went wrong!, User not found in userTenant table');

        return $this->getSupplierDetailsCreation($userTenant->user, $userTenant->tenant_id);
    }

    public function getSupplierDetailsCreation(object $user, int $tenantId)
    {

        $data['name'] = $this->getFormVal($user, $tenantId, 4);
        $data['supCategoryMasterID'] = $this->getFormVal($user, $tenantId, 1);
        $data['address'] = $this->getFormVal($user, $tenantId, 47, 14, 53);
        $data['country_id'] = $this->getFormVal($user, $tenantId, 46, 14, 53);
        $data['phone_number'] = $this->getFormVal($user, $tenantId, 50, 14, 53);
        $data['fax'] = $this->getFormVal($user, $tenantId, 51, 14, 53);
        $data['email'] = $this->getFormVal($user, $tenantId, 69);
        $data['webAddress'] = $this->getFormVal($user, $tenantId, 21);
        $data['currency'] = $this->getFormVal($user, $tenantId, 28);
        $data['nameOnPaymentCheque'] = $this->getFormVal($user, $tenantId, 4);
        $data['registrationNumber'] = $this->getFormVal($user, $tenantId, 8);
        $data['isActive'] = 1;
        $data['vatEligible'] = $this->getFormVal($user, $tenantId, 30);
        $data['vatNumber'] =  $this->getFormVal($user, $tenantId, 36);
        $data['vatPercentage'] =  $this->getFormVal($user, $tenantId, 31);
        return $data;
    }
    public function getFormVal($user, $tenantId, $formId, $formGroupID = null, $isPrimary = null)
    {


        switch ($formGroupID) {
            case 14:
                $supDetail = SupplierDetail::select('form_field_id', 'value')
                    ->where('user_id', $user->id)
                    ->where('tenant_id', $tenantId)
                    ->where('form_group_id', $formGroupID)
                    ->where('status', true)
                    ->orderBy('sort', 'ASC')
                    ->get();

                $data = $supDetail->filter(function ($value, $key) use ($isPrimary) {
                    return $value->form_field_id == $isPrimary;
                });
                $value = array_values($data->toArray());

                $value_key = array_search("1", array_column($value, 'value'));
                if ($value[$value_key]['value'] == 1) {
                    $data = $supDetail->filter(function ($value, $key) use ($formId) {
                        return $value->form_field_id == $formId;
                    });
                    $value = array_values($data->toArray());
                    return isset($value[$value_key]['value']) ? $value[$value_key]['value'] : 0;
                }
                break;
            default:
                $supDetail = SupplierDetail::select('form_field_id', 'value')
                    ->where('user_id', $user->id)
                    ->where('tenant_id', $tenantId)
                    ->where('status', true)
                    ->get();

                $collection = collect($supDetail);
                $data = $collection->filter(function ($value, $key) use ($formId) {
                    return $value->form_field_id == $formId;
                });
                $value = array_values($data->toArray());
                return isset($value[0]['value']) ? $value[0]['value'] : 0;
                break;
        }
    }

    public function getTenantSupplierDetailsHistoryByUUID(string $uuid){
        $userTenant = $this->getUserTenant($uuid);
        return $this->getSupplierDetailsHistory($userTenant->user, $userTenant->tenant_id);
    }

    public function getUserTenant($uuid){
        $userTenant = UserTenant::with(['user'])->where('uuid', $uuid)->first();

        throw_unless($userTenant, 'Invalid UUID pass for fetching supplier data');
        throw_unless($userTenant->user, 'Something went wrong!, User not found in userTenant table');

        return $userTenant;
    }

    public function getSupplierDetailsHistory($user,$tenantId){

        $supplierMasterHistory = SupplierMasterHistory::select('id')
        ->where('user_id',$user->id)
        ->orderBy('id','desc')
        ->first();

        $data = [];

        if($supplierMasterHistory)
        {
            $data = SupplierDetailHistory::where('master_id',$supplierMasterHistory['id'])
            ->with(['field'=> function ($q1){
                $q1->select('id','display_name');
            }])
            ->with(['options'=> function ($q4){
                $q4->select('id','text');
            }])
            ->with(['section' => function ($q2){
                $q2->select('id','display_name');
            }])
            ->with(['group' => function ($q3){
                $q3->select('id','display_name');
            }])
            ->whereDoesntHave('supplierDetail', function ($query) use ($user,$tenantId) {
                $query->where('user_id',$user->id)
                ->where('tenant_id',$tenantId)
                ->whereColumn('value', '=', 'supplier_details_history.value');
            })
            ->with(['supplierDetail' => function ($q)  use ($user,$tenantId){
                $q->with(['options'=> function ($q4){
                    $q4->select('id','text');
                }])
                ->where('user_id',$user->id)
                ->where('tenant_id',$tenantId);
            }])
            ->orderBy('id','desc')
            ->get();
        }


        return $data;
    }
}
