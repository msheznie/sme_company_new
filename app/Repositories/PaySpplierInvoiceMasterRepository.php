<?php

namespace App\Repositories;

use App\Models\PaySpplierInvoiceMaster;
use App\Repositories\BaseRepository;

/**
 * Class PaySpplierInvoiceMasterRepository
 * @package App\Repositories
 * @version August 11, 2024, 2:22 pm +04
*/

class PaySpplierInvoiceMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'companySystemID',
        'companyID',
        'documentSystemID',
        'documentID',
        'projectID',
        'serialNo',
        'companyFinanceYearID',
        'FYBiggin',
        'FYEnd',
        'companyFinancePeriodID',
        'FYPeriodDateFrom',
        'FYPeriodDateTo',
        'BPVcode',
        'BPVdate',
        'BPVbank',
        'BPVAccount',
        'BPVchequeNo',
        'BPVchequeDate',
        'BPVNarration',
        'BPVbankCurrency',
        'BPVbankCurrencyER',
        'directPaymentpayeeYN',
        'directPaymentPayeeSelectEmp',
        'directPaymentPayeeEmpID',
        'directPaymentPayee',
        'directPayeeCurrency',
        'directPayeeBankMemo',
        'BPVsupplierID',
        'supplierGLCodeSystemID',
        'supplierGLCode',
        'supplierTransCurrencyID',
        'supplierTransCurrencyER',
        'supplierDefCurrencyID',
        'supplierDefCurrencyER',
        'localCurrencyID',
        'localCurrencyER',
        'companyRptCurrencyID',
        'companyRptCurrencyER',
        'payAmountBank',
        'payAmountSuppTrans',
        'payAmountSuppDef',
        'payAmountCompLocal',
        'payAmountCompRpt',
        'suppAmountDocTotal',
        'createMonthlyDeduction',
        'confirmedYN',
        'confirmedByEmpSystemID',
        'confirmedByEmpID',
        'confirmedByName',
        'confirmedDate',
        'approved',
        'approvedDate',
        'approvedByUserID',
        'approvedByUserSystemID',
        'postedDate',
        'invoiceType',
        'matchInvoice',
        'trsCollectedYN',
        'trsCollectedByEmpSystemID',
        'trsCollectedByEmpID',
        'trsCollectedByEmpName',
        'trsCollectedDate',
        'trsClearedYN',
        'trsClearedDate',
        'trsClearedByEmpSystemID',
        'trsClearedByEmpID',
        'trsClearedByEmpName',
        'trsClearedAmount',
        'bankClearedYN',
        'bankClearedAmount',
        'bankReconciliationDate',
        'bankClearedDate',
        'bankClearedByEmpSystemID',
        'bankClearedByEmpID',
        'bankClearedByEmpName',
        'chequePaymentYN',
        'chequePrintedYN',
        'chequePrintedDateTime',
        'chequePrintedByEmpSystemID',
        'chequePrintedByEmpID',
        'chequePrintedByEmpName',
        'chequeSentToTreasury',
        'chequeSentToTreasuryByEmpSystemID',
        'chequeSentToTreasuryByEmpID',
        'chequeSentToTreasuryByEmpName',
        'chequeSentToTreasuryDate',
        'chequeReceivedByTreasury',
        'chequeReceivedByTreasuryByEmpSystemID',
        'chequeReceivedByTreasuryByEmpID',
        'chequeReceivedByTreasuryByEmpName',
        'chequeReceivedByTreasuryDate',
        'timesReferred',
        'matchingConfirmedYN',
        'matchingConfirmedByEmpSystemID',
        'matchingConfirmedByEmpID',
        'matchingConfirmedByName',
        'matchingConfirmedDate',
        'refferedBackYN',
        'RollLevForApp_curr',
        'noOfApprovalLevels',
        'isRelatedPartyYN',
        'advancePaymentTypeID',
        'isPdcChequeYN',
        'finalSettlementYN',
        'partyTblID',
        'expenseClaimOrPettyCash',
        'interCompanyToSystemID',
        'interCompanyToID',
        'ReversedYN',
        'cancelYN',
        'cancelComment',
        'cancelDate',
        'cancelledByEmpSystemID',
        'canceledByEmpID',
        'canceledByEmpName',
        'createdUserGroup',
        'createdUserSystemID',
        'createdUserID',
        'createdPcID',
        'modifiedUserSystemID',
        'modifiedUser',
        'modifiedPc',
        'createdDateTime',
        'timestamp',
        'rcmActivated',
        'retentionVatAmount',
        'VATAmount',
        'VATAmountLocal',
        'VATAmountRpt',
        'netAmount',
        'netAmountLocal',
        'netAmountRpt',
        'VATPercentage',
        'pdcChequeYN',
        'bankAccountBalance',
        'payment_mode',
        'advanceAccountSystemID',
        'AdvanceAccount',
        'VATAmountBank',
        'applyVAT'
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
        return PaySpplierInvoiceMaster::class;
    }
}
