<?php

namespace Database\Factories;

use App\Models\SupplierMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SupplierMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uniqueTextcode' => $this->faker->word,
        'primaryCompanySystemID' => $this->faker->randomDigitNotNull,
        'primaryCompanyID' => $this->faker->word,
        'documentSystemID' => $this->faker->randomDigitNotNull,
        'documentID' => $this->faker->word,
        'primarySupplierCode' => $this->faker->word,
        'secondarySupplierCode' => $this->faker->word,
        'supplierName' => $this->faker->text,
        'liabilityAccountSysemID' => $this->faker->randomDigitNotNull,
        'liabilityAccount' => $this->faker->word,
        'UnbilledGRVAccountSystemID' => $this->faker->randomDigitNotNull,
        'UnbilledGRVAccount' => $this->faker->word,
        'address' => $this->faker->text,
        'countryID' => $this->faker->randomDigitNotNull,
        'supplierCountryID' => $this->faker->word,
        'telephone' => $this->faker->word,
        'fax' => $this->faker->word,
        'supEmail' => $this->faker->text,
        'webAddress' => $this->faker->text,
        'currency' => $this->faker->randomDigitNotNull,
        'nameOnPaymentCheque' => $this->faker->word,
        'creditLimit' => $this->faker->randomDigitNotNull,
        'creditPeriod' => $this->faker->randomDigitNotNull,
        'supCategoryMasterID' => $this->faker->randomDigitNotNull,
        'supCategorySubID' => $this->faker->randomDigitNotNull,
        'supplier_category_id' => $this->faker->randomDigitNotNull,
        'supplier_group_id' => $this->faker->randomDigitNotNull,
        'registrationNumber' => $this->faker->word,
        'registrationExprity' => $this->faker->date('Y-m-d H:i:s'),
        'approvedYN' => $this->faker->randomDigitNotNull,
        'approvedEmpSystemID' => $this->faker->randomDigitNotNull,
        'approvedby' => $this->faker->word,
        'approvedDate' => $this->faker->date('Y-m-d H:i:s'),
        'approvedComment' => $this->faker->text,
        'isActive' => $this->faker->randomDigitNotNull,
        'isSupplierForiegn' => $this->faker->randomDigitNotNull,
        'supplierConfirmedYN' => $this->faker->randomDigitNotNull,
        'supplierConfirmedEmpID' => $this->faker->word,
        'supplierConfirmedEmpSystemID' => $this->faker->randomDigitNotNull,
        'supplierConfirmedEmpName' => $this->faker->word,
        'supplierConfirmedDate' => $this->faker->date('Y-m-d H:i:s'),
        'isCriticalYN' => $this->faker->randomDigitNotNull,
        'interCompanyYN' => $this->faker->randomDigitNotNull,
        'companyLinkedToSystemID' => $this->faker->randomDigitNotNull,
        'companyLinkedTo' => $this->faker->word,
        'linkCustomerYN' => $this->faker->randomDigitNotNull,
        'linkCustomerID' => $this->faker->randomDigitNotNull,
        'createdUserGroup' => $this->faker->word,
        'createdPcID' => $this->faker->word,
        'createdUserID' => $this->faker->word,
        'modifiedPc' => $this->faker->word,
        'modifiedUser' => $this->faker->word,
        'createdDateTime' => $this->faker->date('Y-m-d H:i:s'),
        'createdFrom' => $this->faker->randomDigitNotNull,
        'isDirect' => $this->faker->randomDigitNotNull,
        'supplierImportanceID' => $this->faker->randomDigitNotNull,
        'supplierNatureID' => $this->faker->randomDigitNotNull,
        'supplierTypeID' => $this->faker->randomDigitNotNull,
        'WHTApplicable' => $this->faker->randomDigitNotNull,
        'vatEligible' => $this->faker->randomDigitNotNull,
        'vatNumber' => $this->faker->word,
        'vatPercentage' => $this->faker->randomDigitNotNull,
        'retentionPercentage' => $this->faker->randomDigitNotNull,
        'supCategoryICVMasterID' => $this->faker->randomDigitNotNull,
        'supCategorySubICVID' => $this->faker->randomDigitNotNull,
        'isLCCYN' => $this->faker->randomDigitNotNull,
        'isSMEYN' => $this->faker->randomDigitNotNull,
        'isMarkupPercentage' => $this->faker->randomDigitNotNull,
        'markupPercentage' => $this->faker->randomDigitNotNull,
        'RollLevForApp_curr' => $this->faker->randomDigitNotNull,
        'refferedBackYN' => $this->faker->randomDigitNotNull,
        'timesReferred' => $this->faker->randomDigitNotNull,
        'jsrsNo' => $this->faker->word,
        'jsrsExpiry' => $this->faker->word,
        'timestamp' => $this->faker->date('Y-m-d H:i:s'),
        'createdUserSystemID' => $this->faker->randomDigitNotNull,
        'modifiedUserSystemID' => $this->faker->randomDigitNotNull,
        'isBlocked' => $this->faker->randomDigitNotNull,
        'blockedBy' => $this->faker->randomDigitNotNull,
        'blockedDate' => $this->faker->date('Y-m-d H:i:s'),
        'blockedReason' => $this->faker->text,
        'last_activity' => $this->faker->date('Y-m-d H:i:s'),
        'advanceAccountSystemID' => $this->faker->randomDigitNotNull,
        'AdvanceAccount' => $this->faker->word
        ];
    }
}
