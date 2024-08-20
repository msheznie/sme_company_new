<?php

namespace Database\Factories;

use App\Models\ErpBookingSupplierMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class ErpBookingSupplierMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ErpBookingSupplierMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'companySystemID' => $this->faker->randomDigitNotNull,
        'companyID' => $this->faker->word,
        'documentSystemID' => $this->faker->randomDigitNotNull,
        'documentID' => $this->faker->word,
        'projectID' => $this->faker->randomDigitNotNull,
        'serialNo' => $this->faker->randomDigitNotNull,
        'companyFinanceYearID' => $this->faker->randomDigitNotNull,
        'FYBiggin' => $this->faker->date('Y-m-d H:i:s'),
        'FYEnd' => $this->faker->date('Y-m-d H:i:s'),
        'companyFinancePeriodID' => $this->faker->randomDigitNotNull,
        'FYPeriodDateFrom' => $this->faker->date('Y-m-d H:i:s'),
        'FYPeriodDateTo' => $this->faker->date('Y-m-d H:i:s'),
        'bookingInvCode' => $this->faker->word,
        'bookingDate' => $this->faker->date('Y-m-d H:i:s'),
        'comments' => $this->faker->text,
        'secondaryRefNo' => $this->faker->word,
        'supplierID' => $this->faker->randomDigitNotNull,
        'supplierGLCodeSystemID' => $this->faker->randomDigitNotNull,
        'supplierGLCode' => $this->faker->word,
        'UnbilledGRVAccountSystemID' => $this->faker->randomDigitNotNull,
        'UnbilledGRVAccount' => $this->faker->word,
        'supplierInvoiceNo' => $this->faker->word,
        'supplierInvoiceDate' => $this->faker->date('Y-m-d H:i:s'),
        'custInvoiceDirectAutoID' => $this->faker->randomDigitNotNull,
        'supplierTransactionCurrencyID' => $this->faker->randomDigitNotNull,
        'supplierTransactionCurrencyER' => $this->faker->randomDigitNotNull,
        'companyReportingCurrencyID' => $this->faker->randomDigitNotNull,
        'companyReportingER' => $this->faker->randomDigitNotNull,
        'localCurrencyID' => $this->faker->randomDigitNotNull,
        'localCurrencyER' => $this->faker->randomDigitNotNull,
        'bookingAmountTrans' => $this->faker->randomDigitNotNull,
        'bookingAmountLocal' => $this->faker->randomDigitNotNull,
        'bookingAmountRpt' => $this->faker->randomDigitNotNull,
        'confirmedYN' => $this->faker->randomDigitNotNull,
        'confirmedByEmpSystemID' => $this->faker->randomDigitNotNull,
        'confirmedByEmpID' => $this->faker->word,
        'confirmedByName' => $this->faker->word,
        'confirmedDate' => $this->faker->date('Y-m-d H:i:s'),
        'approved' => $this->faker->randomDigitNotNull,
        'approvedDate' => $this->faker->date('Y-m-d H:i:s'),
        'approvedByUserID' => $this->faker->word,
        'approvedByUserSystemID' => $this->faker->randomDigitNotNull,
        'postedDate' => $this->faker->date('Y-m-d H:i:s'),
        'documentType' => $this->faker->randomDigitNotNull,
        'refferedBackYN' => $this->faker->randomDigitNotNull,
        'timesReferred' => $this->faker->randomDigitNotNull,
        'RollLevForApp_curr' => $this->faker->randomDigitNotNull,
        'interCompanyTransferYN' => $this->faker->randomDigitNotNull,
        'createdUserGroup' => $this->faker->word,
        'createdUserSystemID' => $this->faker->randomDigitNotNull,
        'createdUserID' => $this->faker->word,
        'createdPcID' => $this->faker->word,
        'modifiedUserSystemID' => $this->faker->randomDigitNotNull,
        'modifiedUser' => $this->faker->word,
        'modifiedPc' => $this->faker->word,
        'createdDateTime' => $this->faker->word,
        'createdDateAndTime' => $this->faker->date('Y-m-d H:i:s'),
        'cancelYN' => $this->faker->randomDigitNotNull,
        'cancelComment' => $this->faker->text,
        'cancelDate' => $this->faker->date('Y-m-d H:i:s'),
        'canceledByEmpSystemID' => $this->faker->randomDigitNotNull,
        'canceledByEmpID' => $this->faker->word,
        'canceledByEmpName' => $this->faker->word,
        'timestamp' => $this->faker->date('Y-m-d H:i:s'),
        'rcmActivated' => $this->faker->randomDigitNotNull,
        'vatRegisteredYN' => $this->faker->randomDigitNotNull,
        'isLocalSupplier' => $this->faker->randomDigitNotNull,
        'VATAmount' => $this->faker->randomDigitNotNull,
        'VATAmountLocal' => $this->faker->randomDigitNotNull,
        'VATAmountRpt' => $this->faker->randomDigitNotNull,
        'retentionVatAmount' => $this->faker->randomDigitNotNull,
        'retentionDueDate' => $this->faker->word,
        'retentionAmount' => $this->faker->randomDigitNotNull,
        'retentionPercentage' => $this->faker->randomDigitNotNull,
        'netAmount' => $this->faker->randomDigitNotNull,
        'netAmountLocal' => $this->faker->randomDigitNotNull,
        'netAmountRpt' => $this->faker->randomDigitNotNull,
        'VATPercentage' => $this->faker->randomDigitNotNull,
        'serviceLineSystemID' => $this->faker->randomDigitNotNull,
        'wareHouseSystemCode' => $this->faker->randomDigitNotNull,
        'supplierVATEligible' => $this->faker->randomDigitNotNull,
        'employeeID' => $this->faker->randomDigitNotNull,
        'employeeControlAcID' => $this->faker->randomDigitNotNull,
        'createMonthlyDeduction' => $this->faker->randomDigitNotNull,
        'deliveryAppoinmentID' => $this->faker->randomDigitNotNull,
        'whtApplicableYN' => $this->faker->word,
        'whtType' => $this->faker->randomDigitNotNull,
        'whtApplicable' => $this->faker->word,
        'whtAmount' => $this->faker->randomDigitNotNull,
        'whtEdited' => $this->faker->word,
        'whtPercentage' => $this->faker->randomDigitNotNull,
        'isWHTApplicableVat' => $this->faker->word
        ];
    }
}
