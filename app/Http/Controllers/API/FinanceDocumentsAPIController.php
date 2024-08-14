<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Exports\ContractManagmentExport;
use App\Helpers\CreateExcel;
use App\Helpers\General;
use App\Http\Requests\API\CreateFinanceDocumentsAPIRequest;
use App\Http\Requests\API\UpdateFinanceDocumentsAPIRequest;
use App\Models\Company;
use App\Models\CurrencyMaster;
use App\Models\Employees;
use App\Models\FinanceDocuments;
use App\Models\PurchaseOrderMaster;
use App\Repositories\FinanceDocumentsRepository;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FinanceDocumentsResource;
use Response;
use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;
/**
 * Class FinanceDocumentsController
 * @package App\Http\Controllers\API
 */

class FinanceDocumentsAPIController extends AppBaseController
{
    /** @var  FinanceDocumentsRepository */
    private $financeDocumentsRepository;
    protected $errorMessage = 'Finance Documents not found';

    public function __construct(FinanceDocumentsRepository $financeDocumentsRepo)
    {
        $this->financeDocumentsRepository = $financeDocumentsRepo;
    }

    /**
     * Display a listing of the FinanceDocuments.
     * GET|HEAD /financeDocuments
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $financeDocuments = $this->financeDocumentsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(FinanceDocumentsResource::collection($financeDocuments),
            'Finance Documents retrieved successfully');
    }

    /**
     * Store a newly created FinanceDocuments in storage.
     * POST /financeDocuments
     *
     * @param CreateFinanceDocumentsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFinanceDocumentsAPIRequest $request)
    {
        $input = $request->all();
        try
        {
            $this->financeDocumentsRepository->pullFinanceDocumentFromErp($input);
            return $this->sendResponse([], 'Finance document pulled successfully');
        } catch (CommonException $exception)
        {
            return $this->sendError($exception->getMessage());
        } catch (\Exception $exception)
        {
            return $this->sendError($exception->getMessage());
        }
    }

    /**
     * Display the specified FinanceDocuments.
     * GET|HEAD /financeDocuments/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var FinanceDocuments $financeDocuments */
        $financeDocuments = $this->financeDocumentsRepository->find($id);

        if (empty($financeDocuments))
        {
            return $this->sendError($this->errorMessage);
        }

        return $this->sendResponse(new FinanceDocumentsResource($financeDocuments),
            'Finance Documents retrieved successfully');
    }

    /**
     * Update the specified FinanceDocuments in storage.
     * PUT/PATCH /financeDocuments/{id}
     *
     * @param int $id
     * @param UpdateFinanceDocumentsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFinanceDocumentsAPIRequest $request)
    {
        $input = $request->all();

        /** @var FinanceDocuments $financeDocuments */
        $financeDocuments = $this->financeDocumentsRepository->find($id);

        if (empty($financeDocuments))
        {
            return $this->sendError($this->errorMessage);
        }

        $financeDocuments = $this->financeDocumentsRepository->update($input, $id);

        return $this->sendResponse(new FinanceDocumentsResource($financeDocuments),
            'FinanceDocuments updated successfully');
    }

    /**
     * Remove the specified FinanceDocuments from storage.
     * DELETE /financeDocuments/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var FinanceDocuments $financeDocuments */
        $financeDocuments = $this->financeDocumentsRepository->find($id);

        if (empty($financeDocuments))
        {
            return $this->sendError($this->errorMessage);
        }

        $financeDocuments->delete();

        return $this->sendSuccess('Finance Documents deleted successfully');
    }

    public function getFinanceDocumentFilters(Request $request)
    {
        $contractUuid = $request->input('contractUuid');
        $selectedCompanyID = $request->input('selectedCompanyID');
        $documentType = $request->input('documentType');
        try
        {
            $response = $this->financeDocumentsRepository->getFinanceDocumentFilters($contractUuid, $selectedCompanyID,
                $documentType);
            return $this->sendResponse($response, trans('common.data_retrieved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage());

        }
    }

    public function getFinanceSummaryData(Request $request)
    {
        $contractUuid = $request->input('contractUuid') ?? null;
        $companySystemID = $request->input('selectedCompanyID') ?? 0;
        try
        {
            $response = $this->financeDocumentsRepository->getFinanceSummaryData($contractUuid, $companySystemID);
            return $this->sendResponse($response, trans('common.retrieved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    public function printFinancialSummary(Request $request)
    {
        $uuid = $request->get('contractUuid');
        $companySystemID = $request->get('selectedCompanyID');

        $contract = ContractManagementUtils::checkContractExist($uuid, $companySystemID);
        $purchaseOrder = PurchaseOrderMaster::getPurchaseOrder($uuid, $companySystemID);
        $createdAt = Carbon::now();
        $company = Company::getCompanyData($companySystemID);
        $currencyId = Company::getLocalCurrencyID($companySystemID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);

        $retentionSI = FinanceDocuments::getSupplierInvoice($contract['id'], $companySystemID,2,11);
        $retentionPV = FinanceDocuments::getPaymentVoucher($contract['id'], $companySystemID,2,4);
        $milestoneSI = FinanceDocuments::getSupplierInvoice($contract['id'], $companySystemID,1,11);
        $milestonePV = FinanceDocuments::getPaymentVoucher($contract['id'], $companySystemID,1,4);


        $order = array(
            'contract' => $contract,
            'createdAt' => $createdAt,
            'company' => $company,
            'decimalPlaces' => $decimalPlaces,
            'purchaseOrder' => $purchaseOrder,
            'retentionSI' => $retentionSI,
            'retentionPV' => $retentionPV,
            'milestoneSI' => $milestoneSI,
            'milestonePV' => $milestonePV,
        );
        $fileName = 'financial_summary.pdf';
        $html = view('financial_summary', $order);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path('tmp'), 'mode' => 'utf-8', 'format' => 'A4-P',
            'setAutoTopMargin' => 'stretch', 'autoMarginPadding' => -10]);
        $mpdf->AddPage('P');
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->WriteHTML($html);
        return $mpdf->Output($fileName, 'I');
    }
    public function getContractInvoices(Request $request)
    {
        $contractUuid = $request->input('contractUuid');
        $selectedCompanyID = $request->input('selectedCompanyID');
        $documentType = $request->input('documentType');
        $documentID = $request->input('documentID');
        try
        {
            $contractInvoice = $this->financeDocumentsRepository->getContractInvoices($contractUuid,
                $selectedCompanyID, $documentType, $documentID);
            return $this->sendResponse($contractInvoice, trans('common.data_retrieved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage());
        }
    }
    public function getContractPaymentVoucher(Request $request)
    {
        $contractUuid = $request->input('contractUuid');
        $selectedCompanyID = $request->input('selectedCompanyID');
        $documentType = $request->input('documentType');
        $documentID = $request->input('documentID');
        try
        {
            $contractInvoice = $this->financeDocumentsRepository->getContractPaymentVoucher($contractUuid,
                $selectedCompanyID, $documentType, $documentID);
            return $this->sendResponse($contractInvoice, trans('common.data_retrieved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage());
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage());
        }
    }
}
