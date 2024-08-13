<?php

namespace App\Repositories;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Models\DirectPaymentDetails;
use App\Models\ErpBookingSupplierMaster;
use App\Models\Employees;
use App\Models\FinanceDocuments;
use App\Models\ErpDirectInvoiceDetails;
use App\Models\PaySpplierInvoiceMaster;
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

/**
 * Class FinanceDocumentsRepository
 * @package App\Repositories
 * @version August 8, 2024, 2:17 pm +04
*/

class FinanceDocumentsRepository extends BaseRepository
{
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
        return FinanceDocuments::getSupplierInvoice($contractMaster['id'], $selectedCompanyID, $documentType,
            $documentID);
    }
    public function getContractPaymentVoucher($contractUuid, $selectedCompanyID, $documentType, $documentID)
    {
        $contractMaster = ContractManagementUtils::checkContractExist($contractUuid, $selectedCompanyID);
        if(empty($contractMaster))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }
        return FinanceDocuments::getPaymentVoucher($contractMaster['id'], $selectedCompanyID, $documentType,
            $documentID);
    }

    public function getFinanceSummaryData($uuid, $companySystemID)
    {
        $contract = ContractManagementUtils::checkContractExist($uuid, $companySystemID);
        if(empty($contract))
        {
            throw new CommonException(trans('common.contract_not_found'));
        }

        $purchaseOrder = PurchaseOrderMaster::getPurchaseOrder($uuid, $companySystemID);
        $employeeId = General::currentEmployeeId();
        $createdBy = Employees::getEmployee($employeeId);
        $createdAt = Carbon::now();
        $company = Company::getCompanyData($companySystemID);

        return [
            'purchaseOrder' => $purchaseOrder,
            'contract' => $contract,
            'created_by' => $createdBy,
            'created_at' => $createdAt,
            'company' => $company,
        ];
    }


}
