<?php
/**
 * @author Lahiru Dilshan
 * @date 2021-10-21
 */

namespace App\Services\Shared;

use App\Models\Tenant;
use App\Models\UserTenant;
use Illuminate\Support\Facades\Auth;
use Throwable;

class TenantService {
    private $sharedService;

    public function __construct(SharedService $sharedService) {
        $this->sharedService = $sharedService;
    }

    /**
     * get user selected tenant details
     * @param string $tenantId
     * @return mixed
     * @throws Throwable
     */
    public function getTenant($tenantId){
        if(isset($tenantId)){
            return Tenant::where('id', $tenantId)
                ->where('status', true)
                ->first();
        } else {
            return 0;
        }
    }

    /**
     * get tenant by API Key
     * @param string $apiKey
     * @return mixed
     * @throws Throwable
     */
    public function getTenantByAPIKey(string $apiKey){
        throw_unless($apiKey, 'Please provide a API key for get the tenant!');

        return Tenant::where('api_key', $apiKey)
            ->where('status', true)
            ->first();
    }

    /**
     * fetch ERP APIs
     * @param array $data
     * @return mixed
     * @throws Throwable
     */
    public function fetch(array $data) {
        $apiKey = $data['apiKey'];

        if(!$apiKey || $apiKey=='null'){
            if($data['tenantId'] == 0){
                return [];
            }

            $tenant = $this->getTenant($data['tenantId']);
            
            throw_unless($tenant, "Invalid tenant ID");
            if(isset($tenant)){
                $apiKey = $tenant->api_key;
            }
        }
        if($data['isDataTable']){
            return $this->sharedService->fetch([
                'url' => env('ERP_ENDPOINT'),
                'method' => 'POST',
                'data' => [
                    'api_key'       => $apiKey,
                    'request'       => $data['request'],
                    'auth'          => $data['auth'],
                    'extra'         => $data['extra'] ?? null,
                    'search'         => $data['search'] ?? null,
                    'draw'         => $data['draw'] ?? 0,
                    'length'         => $data['length'] ?? 0,
                    'order'         => $data['order'],
                    'start'         => $data['start'],
                    'columns'         => $data['columns'],
                    'supplier_uuid' => $data['supplier_uuid'] ?? null,
                ]
            ]);
        }else{
            return $this->sharedService->fetch([
                'url' => env('ERP_ENDPOINT'),
                'method' => 'POST',
                'data' => [
                    'api_key'       => $apiKey,
                    'request'       => $data['request'],
                    'auth'          => $data['auth'],
                    'extra'         => $data['extra'] ?? null,
                    'supplier_uuid' => $data['supplier_uuid'] ?? null,
                ]
            ]);
        }

    }

    /**
     * get user tenant info
     * @param $user
     * @param $tenantId
     * @return mixed
     * @throws Throwable
     */
    public function getUserTenant($user, $tenantId){
        throw_unless($user, "User not found");
        throw_unless($tenantId, "Invalid tenant ID");

        return UserTenant::where('user_id', $user->id)
            ->where('tenant_id', $tenantId)
            ->whereStatus(true)
            ->orderBy('id', 'DESC')
            ->first();
    }

    /**
     * update user tenant
     * @param $user
     * @param $tenantId
     * @param $data
     * @return mixed
     * @throws Throwable
     */
    public function updateUserTenant($user, $tenantId, $data)
    {
        throw_unless($user, "User not found");
        throw_unless($tenantId, "Invalid tenant ID");

        return UserTenant::where('user_id', $user['id'])
            ->where('tenant_id', $tenantId)
            ->whereStatus(true)
            ->update($data);
    }
}
