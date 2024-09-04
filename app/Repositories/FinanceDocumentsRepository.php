<?php

namespace App\Repositories;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Models\CompanyPolicyMaster;
use App\Models\DirectPaymentDetails;
use App\Models\ErpBookingSupplierMaster;
use App\Models\Employees;
use App\Models\FinanceDocuments;
use App\Models\ErpDirectInvoiceDetails;
use App\Models\PaySpplierInvoiceMaster;
use App\Models\ContractDeliverables;
use App\Repositories\BaseRepository;
use App\Utilities\ContractManagementUtils;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\ContractOverallPenalty;
use App\Models\CurrencyMaster;
use App\Models\PurchaseOrderMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Traits\CrudOperations;

/**
 * Class FinanceDocumentsRepository
 * @package App\Repositories
 * @version August 8, 2024, 2:17 pm +04
*/

class FinanceDocumentsRepository extends BaseRepository
{
    use CrudOperations;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'contract_id',
        'document_type',
        'document_id',
        'document_system_id',
        'company_id',
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
        return FinanceDocuments::class;
    }
    protected function getModel()
    {
        return new FinanceDocuments();
    }

    public function getFinanceDocumentFilters($contractUuid, $selectedCompanyID, $documentType)
    {
        $contract = ContractManagementUtils::checkContractExist($contractUuid, $selectedCompanyID);

        if(empty($contract))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }
        $directInvoiceIds = ErpDirectInvoiceDetails::getContractLinkedWithErp($contractUuid, $selectedCompanyID);
        $invoices = ErpBookingSupplierMaster::getInvoicesForFilters($directInvoiceIds, $selectedCompanyID);
        $directPVInvoices = DirectPaymentDetails::getContractLinkedWithErp($contractUuid, $selectedCompanyID);
        $paymentVoucher = PaySpplierInvoiceMaster::getPaymentVoucherForFilters($directPVInvoices, $selectedCompanyID);
        return [
            'invoices' => $invoices,
            'payment_voucher' => $paymentVoucher,
            'exists_invoice' => FinanceDocuments::getContractFinanceDocument($contract['id'], $documentType, 11),
            'exists_payment_voucher' => FinanceDocuments::getContractFinanceDocument($contract['id'], $documentType, 4)
        ];
    }
    public function pullFinanceDocumentFromErp($formData)
    {
        return DB::transaction( function() use ($formData)
        {
            $contractUuid = $formData['contractUuid'];
            $documentType = $formData['documentType'];
            $selectedCompanyID = $formData['selectedCompanyID'];
            $invoices = $formData['invoices'] ?? [];
            $paymentVoucher = $formData['paymentVouchers'] ?? [];
            $contractMaster = ContractManagementUtils::checkContractExist($contractUuid, $selectedCompanyID);
            if(empty($contractMaster))
            {
                throw new CommonException(trans('common.contract_not_found'));
            }
            self::insertOrDeleteInvoice($invoices, $contractMaster['id'], $documentType, $selectedCompanyID);
            self::insertOrDeletePaymentVoucher($paymentVoucher, $contractMaster['id'],
            $documentType, $selectedCompanyID);
        });
    }
    public function insertOrDeleteInvoice($invoices, $contractID, $documentType, $selectedCompanyID)
    {
        $selectedItemsMap = collect($invoices)->mapWithKeys(function ($item)
        {
            return [$item['id'] => $item['itemName']];
        });
        $selectedInvoiceIds = $selectedItemsMap->keys()->toArray();
        $existingRecords = FinanceDocuments::getExistingRecords($contractID, $documentType, $selectedCompanyID, 11);
        $toInsert = array_diff($selectedInvoiceIds, $existingRecords);
        $toDelete = array_diff($existingRecords, $selectedInvoiceIds);
        $insertRec = [];
        foreach ($toInsert as $id)
        {
            $itemName = $selectedItemsMap[$id];

            $checkInvoiceExists = ErpBookingSupplierMaster::checkSupplierInvoiceMasterExists($id, $selectedCompanyID);
            if (empty($checkInvoiceExists))
            {
                throw new CommonException("Selected Invoice " . $itemName . " does not exist");
            }

            $insertRec[] = [
                'uuid' => ContractManagementUtils::generateUuid(16),
                'contract_id' => $contractID,
                'document_type' => $documentType,
                'document_id' => $checkInvoiceExists['documentSystemID'],
                'document_system_id' => $id,
                'company_id' => $selectedCompanyID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];
        }
        if(!empty($insertRec))
        {
            FinanceDocuments::createFinanceDocuments($insertRec);
        }
        if(!empty($toDelete))
        {
            FinanceDocuments::deleteFinanceDocuments($toDelete, $contractID, $documentType, 11, $selectedCompanyID);
        }
        return true;
    }
    public function insertOrDeletePaymentVoucher($paymentVoucher, $contractID, $documentType, $selectedCompanyID)
    {
        $selectedItemsMap = collect($paymentVoucher)->mapWithKeys(function ($item)
        {
            return [$item['id'] => $item['itemName']];
        });
        $selectedPayVoucherIds = $selectedItemsMap->keys()->toArray();
        $existingRecords = FinanceDocuments::getExistingRecords($contractID, $documentType, $selectedCompanyID, 4);
        $toInsert = array_diff($selectedPayVoucherIds, $existingRecords);
        $toDelete = array_diff($existingRecords, $selectedPayVoucherIds);
        $insertRec = [];
        foreach ($toInsert as $id)
        {
            $itemName = $selectedItemsMap[$id];
            $checkPVExists = PaySpplierInvoiceMaster::checkSupplierPVExists($id, $selectedCompanyID);
            if (empty($checkPVExists))
            {
                throw new CommonException("Selected payment voucher " . $itemName . " does not exist");
            }
            $insertRec[] = [
                'uuid' => ContractManagementUtils::generateUuid(16),
                'contract_id' => $contractID,
                'document_type' => $documentType,
                'document_id' => $checkPVExists['documentSystemID'],
                'document_system_id' => $id,
                'company_id' => $selectedCompanyID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];
        }
        if(!empty($insertRec))
        {
            FinanceDocuments::createFinanceDocuments($insertRec);
        }
        if(!empty($toDelete))
        {
            FinanceDocuments::deleteFinanceDocuments($toDelete, $contractID, $documentType, 4, $selectedCompanyID);
        }
        return true;
    }
    public function getContractInvoices($contractUuid, $selectedCompanyID, $documentType, $documentID)
    {
        $contractMaster = ContractManagementUtils::checkContractExist($contractUuid, $selectedCompanyID);
        if(empty($contractMaster))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }
        $financeDocuments = FinanceDocuments::getSupplierInvoice($contractMaster['id'], $selectedCompanyID,
            $documentType, $documentID);
        return [
            'finance_document' => $financeDocuments,
            'milestones' => ContractManagementUtils::getContractMilestones($contractMaster['id'], $selectedCompanyID),
            'deliverables' => ContractDeliverables::getDeliverablesForFinance($contractMaster['id'], $selectedCompanyID)
        ];
    }
    public function getContractPaymentVoucher($contractUuid, $selectedCompanyID, $documentType, $documentID)
    {
        $contractMaster = ContractManagementUtils::checkContractExist($contractUuid, $selectedCompanyID);
        if(empty($contractMaster))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }
        $financeDocuments = FinanceDocuments::getPaymentVoucher($contractMaster['id'], $selectedCompanyID,
            $documentType, $documentID);
        return [
            'finance_document' => $financeDocuments,
            'milestones' => ContractManagementUtils::getContractMilestones($contractMaster['id'], $selectedCompanyID),
            'deliverables' => ContractDeliverables::getDeliverablesForFinance($contractMaster['id'], $selectedCompanyID)
        ];
    }

    public function getFinanceSummaryData($uuid, $companySystemID)
    {
        $contract = ContractManagementUtils::checkContractExist($uuid, $companySystemID);
        if(empty($contract))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }

        $purchaseOrder = PurchaseOrderMaster::getPurchaseOrder($uuid, $companySystemID);
        $createdAt = Carbon::now();
        $company = Company::getCompanyData($companySystemID);
        $currencyId = Company::getLocalCurrencyID($companySystemID);
        $decimalPlaces = CurrencyMaster::getDecimalPlaces($currencyId);

        return [
            'purchaseOrder' => $purchaseOrder,
            'contract' => $contract,
            'created_at' => $createdAt,
            'company' => $company,
            'decimalPlaces' => $decimalPlaces,
        ];
    }

    public function showFinanceDocument($financeUuid, $documentID, $selectedCompanyID)
    {
        $finance = $this->findByUuid($financeUuid);
        if(empty($finance))
        {
            throw new CommonException('Finance document not found');
        }
        if($documentID == 0)
        {
            throw new CommonException('Document id not found');
        }
        if($documentID == 11)
        {
            return ErpBookingSupplierMaster::getInvoiceMasterDetails($finance['document_system_id']);
        } else
        {
            return PaySpplierInvoiceMaster::paymentVoucherMaster($finance['document_system_id']);
        }
    }
    public function printPaymentVoucher($data)
    {
        $transDecimal = 2;
        $localDecimal = 3;
        $rptDecimal = 2;
        if ($data->transactioncurrency)
        {
            $transDecimal = $data->currency->DecimalPlaces;
        }

        if ($data->localcurrency)
        {
            $localDecimal = $data->currency->DecimalPlaces;
        }

        if ($data->rptcurrency)
        {
            $rptDecimal = $data->currency->DecimalPlaces;
        }
        $order = array(
            'masterdata' => $data,
            'transDecimal' => $transDecimal,
            'localDecimal' => $localDecimal,
            'rptDecimal' => $rptDecimal
        );

        $fileName = 'payment_voucher.pdf';
        $html = view('payment_voucher_print', $order);
        $htmlFooter = view('payment_voucher_print_footer', $order);

        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path('tmp'), 'mode' => 'utf-8', 'format' => 'A4-P',
            'setAutoTopMargin' => 'stretch', 'autoMarginPadding' => -10]);
        $mpdf->AddPage('P');
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return $mpdf->Output($fileName, 'I');
    }
    public function printInvoice($supplierInvoice, $companySystemID)
    {
        $transDecimal = 2;
        $localDecimal = 3;
        $rptDecimal = 2;

        if ($supplierInvoice->transactioncurrency)
        {
            $transDecimal = $supplierInvoice->currecny->DecimalPlaces;
        }

        if ($supplierInvoice->localcurrency)
        {
            $localDecimal = $supplierInvoice->localcurrency->DecimalPlaces;
        }

        if ($supplierInvoice->rptcurrency)
        {
            $rptDecimal = $supplierInvoice->rptcurrency->DecimalPlaces;
        }
        $directTotTra = ErpDirectInvoiceDetails::getSum($supplierInvoice['bookingSuppMasInvAutoID'], 'DIAmount');
        $directTotVAT = ErpDirectInvoiceDetails::getSum($supplierInvoice['bookingSuppMasInvAutoID'], 'VATAmount');
        $directTotNet = ErpDirectInvoiceDetails::getSum($supplierInvoice['bookingSuppMasInvAutoID'], 'netAmount');
        $directTotLoc = ErpDirectInvoiceDetails::getSum($supplierInvoice['bookingSuppMasInvAutoID'], 'localAmount');

        $isProjectBase = CompanyPolicyMaster::checkActiveCompanyPolicy($companySystemID, 56);

        $order = array(
            'masterdata' => $supplierInvoice,
            'transDecimal' => $transDecimal,
            'localDecimal' => $localDecimal,
            'rptDecimal' => $rptDecimal,
            'directTotTra' => $directTotTra,
            'directTotVAT' => $directTotVAT,
            'directTotNet' => $directTotNet,
            'directTotLoc' => $directTotLoc,
            'isProjectBase' => $isProjectBase
        );

        $fileName = 'invoice.pdf';
        $html = view('invoice_print', $order);
        $htmlFooter = view('invoice_print_footer', $order);
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path('tmp'), 'mode' => 'utf-8', 'format' => 'A4-P',
            'setAutoTopMargin' => 'stretch', 'autoMarginPadding' => -10]);
        $mpdf->AddPage('P');
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return $mpdf->Output($fileName, 'I');
    }
    public function generateSummaries($documents)
    {
        foreach ($documents as $document)
        {
            $milestoneSummary = collect($document->milestoneList)
                ->pluck('milestone.title')
                ->filter()
                ->implode(', ');
            $deliverableSummary = collect($document->deliverableList)
                ->map(function ($d)
                {
                    $milestoneTitle = $d->deliverable->milestone->title ?? '';
                    $deliverableTitle = $d->deliverable->title;
                    return trim("$milestoneTitle | $deliverableTitle", '| ');
                })
                ->filter()
                ->implode(', ');

            $document->milestoneSummary = $milestoneSummary;
            $document->deliverableSummary = $deliverableSummary;
        }

        return $documents;
    }
}
