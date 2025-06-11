<?php

namespace Database\Factories;

use App\Models\SupplierAssigned;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierAssignedFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SupplierAssigned::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'supplierCodeSytem' => $this->faker->randomDigitNotNull,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'companyID' => $this->faker->word,
        'uniqueTextcode' => $this->faker->word,
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
        'registrationExprity' => $this->faker->word,
        'supplierImportanceID' => $this->faker->randomDigitNotNull,
        'supplierNatureID' => $this->faker->randomDigitNotNull,
        'supplierTypeID' => $this->faker->randomDigitNotNull,
        'WHTApplicable' => $this->faker->randomDigitNotNull,
        'vatEligible' => $this->faker->randomDigitNotNull,
        'vatNumber' => $this->faker->word,
        'vatPercentage' => $this->faker->randomDigitNotNull,
        'supCategoryICVMasterID' => $this->faker->randomDigitNotNull,
        'supCategorySubICVID' => $this->faker->randomDigitNotNull,
        'isLCCYN' => $this->faker->randomDigitNotNull,
        'isMarkupPercentage' => $this->faker->randomDigitNotNull,
        'markupPercentage' => $this->faker->randomDigitNotNull,
        'isRelatedPartyYN' => $this->faker->randomDigitNotNull,
        'isCriticalYN' => $this->faker->randomDigitNotNull,
        'jsrsNo' => $this->faker->word,
        'jsrsExpiry' => $this->faker->word,
        'isActive' => $this->faker->randomDigitNotNull,
        'isAssigned' => $this->faker->randomDigitNotNull,
        'timestamp' => $this->faker->date('Y-m-d H:i:s'),
        'isBlocked' => $this->faker->randomDigitNotNull,
        'blockedBy' => $this->faker->randomDigitNotNull,
        'blockedDate' => $this->faker->date('Y-m-d H:i:s'),
        'blockedReason' => $this->faker->text,
        'createdFrom' => $this->faker->randomDigitNotNull,
        'advanceAccountSystemID' => $this->faker->randomDigitNotNull,
        'AdvanceAccount' => $this->faker->word
        ];
    }
}
