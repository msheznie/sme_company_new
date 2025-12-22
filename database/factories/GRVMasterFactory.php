<?php

namespace Database\Factories;

use App\Models\GRVMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class GRVMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GRVMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'grvTypeID' => $this->faker->randomDigitNotNull,
        'grvType' => $this->faker->word,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'companyID' => $this->faker->word,
        'serviceLineSystemID' => $this->faker->randomDigitNotNull,
        'serviceLineCode' => $this->faker->word,
        'companyAddress' => $this->faker->word,
        'companyFinanceYearID' => $this->faker->randomDigitNotNull,
        'companyFinancePeriodID' => $this->faker->randomDigitNotNull,
        'FYBiggin' => $this->faker->date('Y-m-d H:i:s'),
        'FYEnd' => $this->faker->date('Y-m-d H:i:s'),
        'documentSystemID' => $this->faker->randomDigitNotNull,
        'documentID' => $this->faker->word,
        'projectID' => $this->faker->randomDigitNotNull,
        'grvDate' => $this->faker->date('Y-m-d H:i:s'),
        'stampDate' => $this->faker->date('Y-m-d H:i:s'),
        'grvSerialNo' => $this->faker->randomDigitNotNull,
        'grvPrimaryCode' => $this->faker->word,
        'grvDoRefNo' => $this->faker->word,
        'grvNarration' => $this->faker->text,
        'grvLocation' => $this->faker->randomDigitNotNull,
        'grvDOpersonName' => $this->faker->word,
        'grvDOpersonResID' => $this->faker->word,
        'grvDOpersonTelNo' => $this->faker->word,
        'grvDOpersonVehicleNo' => $this->faker->word,
        'supplierID' => $this->faker->randomDigitNotNull,
        'supplierPrimaryCode' => $this->faker->word,
        'supplierName' => $this->faker->word,
        'supplierAddress' => $this->faker->text,
        'supplierTelephone' => $this->faker->word,
        'supplierFax' => $this->faker->word,
        'supplierEmail' => $this->faker->word,
        'liabilityAccountSysemID' => $this->faker->randomDigitNotNull,
        'liabilityAccount' => $this->faker->word,
        'UnbilledGRVAccountSystemID' => $this->faker->randomDigitNotNull,
        'UnbilledGRVAccount' => $this->faker->word,
        'localCurrencyID' => $this->faker->randomDigitNotNull,
        'localCurrencyER' => $this->faker->randomDigitNotNull,
        'companyReportingCurrencyID' => $this->faker->randomDigitNotNull,
        'companyReportingER' => $this->faker->randomDigitNotNull,
        'supplierDefaultCurrencyID' => $this->faker->randomDigitNotNull,
        'supplierDefaultER' => $this->faker->randomDigitNotNull,
        'supplierTransactionCurrencyID' => $this->faker->randomDigitNotNull,
        'supplierTransactionER' => $this->faker->randomDigitNotNull,
        'grvConfirmedYN' => $this->faker->randomDigitNotNull,
        'grvConfirmedByEmpSystemID' => $this->faker->randomDigitNotNull,
        'grvConfirmedByEmpID' => $this->faker->word,
        'grvConfirmedByName' => $this->faker->word,
        'grvConfirmedDate' => $this->faker->date('Y-m-d H:i:s'),
        'grvCancelledYN' => $this->faker->randomDigitNotNull,
        'grvCancelledBySystemID' => $this->faker->randomDigitNotNull,
        'grvCancelledBy' => $this->faker->word,
        'grvCancelledByName' => $this->faker->word,
        'grvCancelledDate' => $this->faker->date('Y-m-d H:i:s'),
        'grvCancelledComment' => $this->faker->text,
        'grvTotalComRptCurrency' => $this->faker->randomDigitNotNull,
        'grvTotalLocalCurrency' => $this->faker->randomDigitNotNull,
        'grvTotalSupplierDefaultCurrency' => $this->faker->randomDigitNotNull,
        'grvTotalSupplierTransactionCurrency' => $this->faker->randomDigitNotNull,
        'grvDiscountPercentage' => $this->faker->randomDigitNotNull,
        'grvDiscountAmount' => $this->faker->randomDigitNotNull,
        'approved' => $this->faker->randomDigitNotNull,
        'approvedDate' => $this->faker->date('Y-m-d H:i:s'),
        'approvedByUserID' => $this->faker->word,
        'approvedByUserSystemID' => $this->faker->randomDigitNotNull,
        'postedDate' => $this->faker->date('Y-m-d H:i:s'),
        'refferedBackYN' => $this->faker->randomDigitNotNull,
        'timesReferred' => $this->faker->randomDigitNotNull,
        'RollLevForApp_curr' => $this->faker->randomDigitNotNull,
        'invoiceBeforeGRVYN' => $this->faker->randomDigitNotNull,
        'deliveryConfirmedYN' => $this->faker->randomDigitNotNull,
        'interCompanyTransferYN' => $this->faker->randomDigitNotNull,
        'FromCompanySystemID' => $this->faker->randomDigitNotNull,
        'FromCompanyID' => $this->faker->word,
        'capitalizedYN' => $this->faker->randomDigitNotNull,
        'isMarkupUpdated' => $this->faker->randomDigitNotNull,
        'createdUserGroup' => $this->faker->word,
        'createdPcID' => $this->faker->word,
        'createdUserSystemID' => $this->faker->randomDigitNotNull,
        'createdUserID' => $this->faker->word,
        'modifiedPc' => $this->faker->word,
        'modifiedUserSystemID' => $this->faker->randomDigitNotNull,
        'modifiedUser' => $this->faker->word,
        'createdDateTime' => $this->faker->date('Y-m-d H:i:s'),
        'timeStamp' => $this->faker->date('Y-m-d H:i:s'),
        'pullType' => $this->faker->randomDigitNotNull,
        'mfqJobID' => $this->faker->randomDigitNotNull,
        'vatRegisteredYN' => $this->faker->randomDigitNotNull,
        'deliveryAppoinmentID' => $this->faker->randomDigitNotNull
        ];
    }
}
