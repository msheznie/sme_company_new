<?php

namespace App\Services\ERP;

use App\Models\Tenant;
use App\Services\Supplier\SupplierService;
use Illuminate\Http\Request;
use Exception;
use Throwable;

class ERPService
{
    private $supplierService = null;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getTenantByApiKey(Request $request)
    {
        $apiKey = $request->all('api_key');
        return Tenant::where('api_key', '=', $apiKey)->first();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getSupplierDetails(Request $request) {
        try {
            $data = $this->supplierService->getTenantSupplierDetailsByUUID($request->input('extra.uuid'));

            return [
                'success' => true,
                'message' => 'Supplier details successfully fetched',
                'data' => $data
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * update KYC status
     * @param Request $request
     * @return array
     * @throws Throwable
     */
    public function updateSupplierKYCStatus(Request $request){
        try {
            $userEmail = isset($request->extra['email']) ? $request->extra['email'] : null;
            $name = isset($request->extra['name']) ? $request->extra['name'] : null;
            $data = $this->supplierService->updateSupplierKYCStatus($request->input('extra.uuid'), $request->input('extra.status'),$userEmail,$name);

            throw_unless($data, "Status couldn't uploaded");

            return [
                'success' => true,
                'message' => 'Supplier KYC Status has been updated',
                'data' => $data
            ];
        } catch (Exception $e) {
            return [
                'success' => true,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function getsupplierdetailcreation(Request $request){
        try {
            $data = $this->supplierService->getTenantSupplierDetailsCreationByUUID($request->input('extra.uuid'));

            return [
                'success' => true,
                'message' => 'Supplier details successfully fetched',
                'data' => $data
            ];
        } catch (Exception $e) {
            return [
                'success' => true,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function getSupplierDetailsHistory(Request $request) {
        try {
            $data = $this->supplierService->getTenantSupplierDetailsHistoryByUUID($request->input('extra.uuid'));

            return [
                'success' => true,
                'message' => 'Supplier history details successfully fetched',
                'data' => $data
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

}
