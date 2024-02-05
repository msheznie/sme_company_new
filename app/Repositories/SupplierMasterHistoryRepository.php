<?php

namespace App\Repositories;

use App\Models\SupplierDetail;
use App\Models\SupplierDetailHistory;
use App\Models\SupplierMasterHistory;
use App\Repositories\BaseRepository;
use App\Services\Shared\SharedService;
use App\Services\Shared\TenantService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Class SupplierMasterHistoryRepository
 * @package App\Repositories
 * @version November 13, 2023, 6:47 am +04
 */

class SupplierMasterHistoryRepository extends BaseRepository
{

    protected $sharedService;
    protected $tenantService;

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'edit_referred',
        'ammend_comment',
        'user_id',
        'tenant_id'
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
        return SupplierMasterHistory::class;
    }

    public function __construct(SharedService $sharedService, TenantService $tenantService)
    {
        $this->sharedService = $sharedService;
        $this->tenantService = $tenantService;
    }


    public function saveAmmendData(Request $request)
    {
        $input = $request->all();

        $validateInputs = $this->validateInputs($input);
        if (!$validateInputs['status']) {
            return $validateInputs;
        }

        DB::beginTransaction();
        try {

            $userId = $input['userId'];
            $tenantId = $input['tenantId'];
            $comment = $input['comment'];
            $uuid = $input['uuid'];
            $userTenantId = $input['userTenantId'];

            $approvalReversalKYC = $this->approvalReversalKYC($uuid, $tenantId);

            if (!$approvalReversalKYC['status']) {
                return ['status' => false, 'message' => $approvalReversalKYC['message']];
            }

            $supplierMasterHistory = $this->InsertSupplierMasterHistory($userId, $tenantId, $comment,$userTenantId);

            if (!$supplierMasterHistory['status']) {
                return ['status' => false, 'message' => $supplierMasterHistory['message']];
            }

            $historyMasterId = $supplierMasterHistory['data'];

            $supplierDetailHistory = $this->InsertSupplierDetailHistory($userId, $tenantId, $historyMasterId);

            if (!$supplierDetailHistory['status']) {
                return ['status' => false, 'message' => $supplierDetailHistory['message']];
            }

            DB::commit();
            return ['status' => true, 'message' => 'KYC Approval ammend process success'];
        } catch (\Exception $e) {
            DB::rollback();
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function InsertSupplierMasterHistory($userId, $tenantId, $comment,$userTenantId)
    {

        $timeReferred = SupplierMasterHistory::where('tenant_id', $tenantId)
            ->where('user_id', $userId)
            ->max('edit_referred') + 1;

        $insertSupplierHistory = [
            'ammend_comment' => $comment,
            'user_id' => $userId,
            'tenant_id' => $tenantId,
            'user_tenant_id' => $userTenantId,
            'edit_referred' => $timeReferred,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $result = SupplierMasterHistory::insertGetId($insertSupplierHistory);

        if (!$result) {
            return ['status' => false, 'message' => $result];
        }

        return ['status' => true, 'message' => 'Supplier master history insertion success', 'data' => $result];
    }

    public function InsertSupplierDetailHistory($userId, $tenantId, $historyMasterId)
    {
        $supplierDetails =  SupplierDetail::where('tenant_id', $tenantId)
            ->where('user_id', $userId)
            ->get();

        $recordsToInsert = $supplierDetails->map(function ($record) use ($historyMasterId) {
            $record['master_id'] = $historyMasterId;
            return Arr::except($record, ['id']);;
        })->toArray();

        $result = SupplierDetailHistory::insert($recordsToInsert);

        if (!$result) {
            return ['status' => false, 'message' => $result];
        }

        return ['status' => true, 'message' => 'Supplier detail history insertion success', 'data' => $result];
    }

    public function approvalReversalKYC($uuid, $tenantId)
    {
        $api = $this->tenantService->getTenant($tenantId);
        $apiKey = $api->api_key;

        $ERPFormData = $this->sharedService->fetch([
            'url' => env('ERP_ENDPOINT'),
            'method' => 'POST',
            'data' => [
                'api_key'       => $apiKey,
                'request'       => 'SUPPLIER_REGISTRATION_APPROVAL_AMMEND',
                'supplier_uuid' => $uuid,
                'approvalAmmend' => 1
            ]
        ]); 

        if (!$ERPFormData->success) {
            return ['status' => false, 'message' => "Something went wrong with approval reversal."];
        }

        return ['status' => true, 'message' => "success"];
    }

    public function validateInputs($input)
    {
        if (!isset($input['userTenantId']) || $input['userTenantId'] == null) {
            return ['status' => false, 'message' => 'User tenant id is required'];
        }

        if (!isset($input['uuid']) || $input['uuid'] == null) {
            return ['status' => false, 'message' => 'uuid id is required'];
        }

        if (!isset($input['userId']) || $input['userId'] == null) {
            return ['status' => false, 'message' => 'User id is required'];
        }

        if (!isset($input['tenantId']) || $input['tenantId'] == null) {
            return ['status' => false, 'message' => 'Tenant id is required'];
        }

        if (!isset($input['comment']) || $input['comment'] == null) {
            return ['status' => false, 'message' => 'Comment is required'];
        }

        return ['status' => true, 'message' => "success"];
    }
}
