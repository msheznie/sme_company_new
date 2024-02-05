<?php

namespace App\Http\Controllers\ERP;

use App\Http\Controllers\Controller;
use App\Services\ERP\ERPService;
use Illuminate\Http\Request;


// available apis name define here
define('GET_SUPPLIER_DETAILS', 'GET_SUPPLIER_DETAILS');
define('UPDATE_KYC_STATUS', 'UPDATE_KYC_STATUS');
define('GET_SUPPLIER_DETAILS_CREATIONS', 'GET_SUPPLIER_DETAILS_CREATIONS');
define('GET_SUPPLIER_DETAIL_CREATIONS', 'GET_SUPPLIER_DETAIL_CREATIONS');
define('GET_SUPPLIER_HISTORY_DETAILS', 'GET_SUPPLIER_HISTORY_DETAILS');

class APIController extends Controller
{
    private $erpService = null;

    public function __construct(ERPService $erpService)
    {
        $this->erpService = $erpService;
    }

    /**
     * handle api request
     * @param Request $request
     * @return array
     */
    public function handleRequest(Request $request)
    {
        switch ($request->input('request')) {
            case GET_SUPPLIER_DETAILS:
                return $this->erpService->getSupplierDetails($request);
            case UPDATE_KYC_STATUS:
                return $this->erpService->updateSupplierKYCStatus($request);
            case GET_SUPPLIER_DETAIL_CREATIONS:
                return $this->erpService->getSupplierDetailCreation($request);
                case GET_SUPPLIER_HISTORY_DETAILS:
                return $this->erpService->getSupplierDetailsHistory($request);
            default:
                return [
                    'success' => false,
                    'message' => 'Requested API not available, please recheck!',
                    'data' => null
                ];
        }
    }
}
