<?php

namespace App\Http\Controllers\shared;

use App\Http\Controllers\Controller;
use App\Interfaces\SupplierDetailsRepositoryInterface;
use App\Interfaces\UserTenantRepositoryInterface;
use App\Models\Tenant;
use App\Models\UserTenant;
use App\Services\Shared\TenantService;
use App\Services\Supplier\SupplierService;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class TenantController extends Controller
{
    private $tenantService = null;
    /**
     * @var SupplierDetailsRepositoryInterface
     */
    private $supplierDetailsRepository;
    /**
     * @var UserTenantRepositoryInterface
     */
    private $userTenantRepository;

    private $supplierService = null;

    public function __construct(
        TenantService $tenantService,
        SupplierDetailsRepositoryInterface $supplierDetailsRepository,
        UserTenantRepositoryInterface $userTenantRepository,
        SupplierService $supplierService
    ) {
        $this->tenantService = $tenantService;
        $this->supplierDetailsRepository = $supplierDetailsRepository;
        $this->userTenantRepository = $userTenantRepository;
        $this->supplierService = $supplierService;
    }

    /**
     * fetch tenant apis
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function fetch(Request $request): JsonResponse
    {
        try {
            if ($request->input('isDataTable')) {
                $response = $this->tenantService->fetch([
                    'tenantId' => $request->input('tenantId'),
                    'apiKey' => $request->input('apiKey'),
                    'request' => $request->input('request'),
                    'auth' => $request->user(),
                    'extra' => $request->input('extra'),
                    'search' => $request->input('search'),
                    'draw' => $request->input('draw'),
                    'length' => $request->input('length'),
                    'order' => $request->input('order'),
                    'start' => $request->input('start'),
                    'columns' => $request->input('columns'),
                    'isDataTable' => true,
                    'supplier_uuid' => $this->getSupplierUuid($request)
                ]);
            }else{
                $response = $this->tenantService->fetch([
                    'tenantId' => $request->input('tenantId'),
                    'apiKey' => $request->input('apiKey'),
                    'request' => $request->input('request'),
                    'auth' => $request->user(),
                    'extra' => $request->input('extra'),
                    'isDataTable' => false,
                    'supplier_uuid' => $this->getSupplierUuid($request)
                ]);
            }

            //Log::info(print_r($response, true));

            // throw_unless($response, "Invalid API Key or Something went wrong in ERP");
            // throw_unless($response && $response->data, $response->message ?? "Something went wrong!, API couldn't fetch");
            if($response && $response->data)
            {
                return response()->json($response);
            }
            else if($response && $response->message)
            {
                return response()->json([
                    'success' => false,
                    'message' => $response->message,
                    'data' => $response->message
                ], 500);
            }

            return response()->json([
                'success' => false,
                'message' => "Something went wrong!, API couldn't fetch",
                'data' => "Something went wrong!, API couldn't fetch"
            ], 500);

            // return response()->json($response);
        } catch (RequestException $e) {
            $exception = $e->getMessage();

            \Log::info([
                'type' => 'ERP',
                'desc' => 'ERP API call Errors',
                'exception' => $exception
            ]);

            return response()->json([
                'success' => false,
                'message' => $exception,
                'data' => $exception
            ], 500);
        }
    }

    /**
     * List user tenant list : use for list tenants
     * @param $userId
     * @return JsonResponse
     */
    public function getTenantList()
    {
        $user =  Auth::user();
        try {
            return $this->userTenantRepository->getTenantList($user->id);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Check is a record for tenant with user
     * @param $userId
     * @param $apiKey
     * @return JsonResponse
     */
    public function isTenantExist($userId, $apiKey)
    {
        try {
            $tenantRegisterCount = $this->userTenantRepository->isTenantRegistered($userId, $apiKey);

            return response()->json([
                'success' => true,
                'message' => "Tenant already has account",
                'data' => $tenantRegisterCount
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Store User & Tenant Details in the user_tenant table
     * @param Request $request
     * @return JsonResponse
     */
    public function storeTenant(Request $request)
    {
        try {
            $userTenantUuid = $this->userTenantRepository->save($request);

            return response()->json([
                'success' => true,
                'message' => $request,
                'data' => $userTenantUuid
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * get the kyc status from the user_tenant table
     * @param $userId
     * @param $tenantId
     * @return JsonResponse
     */
    public function getKycStatus($userId, $tenantId)
    {
        try {
            return $this->userTenantRepository->getKycStatus($userId, $tenantId);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * @param $request
     * @return string |null
     * @throws Throwable
     */
    private function getSupplierUuid($request)
    {
        try {
            $tenantId = $request->input('tenantId');

            if(!$tenantId){
                $tenant = Tenant::where('api_key', $request->input('apiKey'))->first();

                throw_unless($tenant, 'Invalid API key');

                $tenantId = $tenant->id;
            }

            $user = $request->user();
            if($user) {
                $userTenant = UserTenant::select('uuid')
                    ->where([
                        ['user_id', $user->id],
                        ['tenant_id', $tenantId]
                    ])
                    ->first();

                if(!empty($userTenant)){
                    return $userTenant->uuid;
                }
            }

            return '';
        } catch (Exception $e) {
            return '';
        }
    }
}
