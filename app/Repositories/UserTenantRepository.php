<?php

namespace App\Repositories;

use App\Interfaces\UserTenantRepositoryInterface;
use App\Models\FormField;
use App\Models\SupplierDetail;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserTenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserTenantRepository implements UserTenantRepositoryInterface
{
    public function save($request)
    {
        $invitationData = $request->input('invitationData');
        $tenantId = $this->getTenantId($invitationData[2]);
        $uuid = Str::uuid()->toString();

        $userTenant = new UserTenant();
        $userTenant->user_id = $invitationData[1];
        $userTenant->tenant_id = $tenantId->id;
        $userTenant->company_id = $invitationData[0]['company_id'];
        $userTenant->status = 1;
        $userTenant->kyc_status = 0;
        $userTenant->uuid = $uuid;
        $userTenant->is_bid_tender = $invitationData[0]['is_bid_tender'];

        $user = User::select('name','registration_number','name','email')
                     ->where('id',$userTenant->user_id)
                     ->first();

        $form_fields = FormField::select('id','sort')
                    ->whereIn('id',[4,8,69])
                    ->get();

        $fieldValues = [
            4 => $user->name,
            8 => $user->registration_number,
            69 => $user->email
        ];

        foreach($form_fields as $val){
            $data = [
                'user_id' => $userTenant->user_id,
                'tenant_id' => $tenantId->id,
                'form_section_id' => 1,
                'form_group_id' => 1,
                'form_field_id' => $val['id'],
                'sort' => $val['sort'],
                'value' => $fieldValues[$val['id']] ?? null,
                'status' => 1,
            ];

            SupplierDetail::create($data);
        }

        //set default attachments
        $supplierDetailsData = $this->prepareSupplierDetailsData($userTenant->user_id, $tenantId->id);
        SupplierDetail::insert($supplierDetailsData);
        $userTenant->email = $user->email;
        $userTenant->save();
        return $userTenant;
    }

    public function getTenantList($userId){
        return UserTenant::query()
            ->with(array('tenants' => function($query) {
                $query->select('id','name');
            }))
            ->where([
                ['user_id' , '=',  $userId],
                ['status' , '=',  1]
            ])
            ->get()
            ->map(function ($tenants){
                return [
                    'id' => $tenants->tenant_id,
                    'name' => $tenants->tenants[0]->name,
                    'kyc_status' => $tenants->kyc_status,
                    'is_bid_tender' => $tenants->is_bid_tender,
                ];
            });
    }

    public function isTenantRegistered($userId, $apiKey){
        return $registeredTenantCount =  DB::table('user_tenant')
            ->join('tenants', 'tenants.id', '=', 'user_tenant.tenant_id')
            ->where([
                ['tenants.api_key', $apiKey],
                ['user_tenant.status', 1],
                ['user_tenant.user_id', $userId],
            ])
            ->exists();
    }

    public function getTenantId($api_key){
        $tenantId = Tenant::select('id')->where([
            ['api_key', $api_key],
        ])
            ->first();

        if(is_null($tenantId)){
            return false;
        }

        return $tenantId;
    }
    public function getUserTenantData($userId){
        $isBidTender = UserTenant::select('is_bid_tender')
        ->where([
            ['user_id' , '=',  $userId],
            ['status' , '=',  1]
        ])
        ->first();
        if($isBidTender){
            return $isBidTender->is_bid_tender;
        }else{
            return 0;
        }

    }

    private function prepareSupplierDetailsData($userId, $tenantId) {
        $currentTime = now();

        return [
            // Record 1
            [
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'form_section_id' => 1,
                'form_group_id' => 3,
                'form_field_id' => 11,
                'form_data_id' => null,
                'sort' => 12,
                'value' => 'Company Registration Certificate',
                'status' => 1,
                'created_at' => $currentTime,
                'updated_at' => null,
            ],
            // Record 2
            [
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'form_section_id' => 1,
                'form_group_id' => 3,
                'form_field_id' => 12,
                'form_data_id' => null,
                'sort' => 12,
                'value' => '-',
                'status' => 1,
                'created_at' => $currentTime,
                'updated_at' => null,
            ],
            // Record 3
            [
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'form_section_id' => 1,
                'form_group_id' => 3,
                'form_field_id' => 14,
                'form_data_id' => null,
                'sort' => 12,
                'value' => '-',
                'status' => 1,
                'created_at' => $currentTime,
                'updated_at' => null,
            ],
            // Record 4
            [
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'form_section_id' => 1,
                'form_group_id' => 3,
                'form_field_id' => 11,
                'form_data_id' => null,
                'sort' => 13,
                'value' => 'Supplier Profile Document',
                'status' => 1,
                'created_at' => $currentTime,
                'updated_at' => null,
            ],
            // Record 5
            [
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'form_section_id' => 1,
                'form_group_id' => 3,
                'form_field_id' => 12,
                'form_data_id' => null,
                'sort' => 13,
                'value' => '-',
                'status' => 1,
                'created_at' => $currentTime,
                'updated_at' => null,
            ],
            // Record 6
            [
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'form_section_id' => 1,
                'form_group_id' => 3,
                'form_field_id' => 14,
                'form_data_id' => null,
                'sort' => 13,
                'value' => '-',
                'status' => 1,
                'created_at' => $currentTime,
                'updated_at' => null,
            ],
            // Record 7
            [
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'form_section_id' => 1,
                'form_group_id' => 3,
                'form_field_id' => 11,
                'form_data_id' => null,
                'sort' => 14,
                'value' => 'Riyadhah Card',
                'status' => 1,
                'created_at' => $currentTime,
                'updated_at' => null,
            ],
            // Record 8
            [
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'form_section_id' => 1,
                'form_group_id' => 3,
                'form_field_id' => 12,
                'form_data_id' => null,
                'sort' => 14,
                'value' => '-',
                'status' => 1,
                'created_at' => $currentTime,
                'updated_at' => null,
            ],
            // Record 9
            [
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'form_section_id' => 1,
                'form_group_id' => 3,
                'form_field_id' => 14,
                'form_data_id' => null,
                'sort' => 14,
                'value' => '-',
                'status' => 1,
                'created_at' => $currentTime,
                'updated_at' => null,
            ],
            // Record 10
            [
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'form_section_id' => 1,
                'form_group_id' => 3,
                'form_field_id' => 11,
                'form_data_id' => null,
                'sort' => 15,
                'value' => 'Omanisation',
                'status' => 1,
                'created_at' => $currentTime,
                'updated_at' => null,
            ],
            // Record 11
            [
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'form_section_id' => 1,
                'form_group_id' => 3,
                'form_field_id' => 12,
                'form_data_id' => null,
                'sort' => 15,
                'value' => '-',
                'status' => 1,
                'created_at' => $currentTime,
                'updated_at' => null,
            ],
            // Record 12
            [
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'form_section_id' => 1,
                'form_group_id' => 3,
                'form_field_id' => 14,
                'form_data_id' => null,
                'sort' => 15,
                'value' => '-',
                'status' => 1,
                'created_at' => $currentTime,
                'updated_at' => null,
            ],
        ];
    }
}
