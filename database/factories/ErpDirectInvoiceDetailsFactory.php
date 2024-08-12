<?php

namespace Database\Factories;

use App\Models\ErpDirectInvoiceDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class ErpDirectInvoiceDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ErpDirectInvoiceDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'directInvoiceAutoID' => $this->faker->randomDigitNotNull,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'companyID' => $this->faker->word,
        'serviceLineSystemID' => $this->faker->randomDigitNotNull,
        'serviceLineCode' => $this->faker->word,
        'chartOfAccountSystemID' => $this->faker->randomDigitNotNull,
        'glCode' => $this->faker->word,
        'glCodeDes' => $this->faker->word,
        'comments' => $this->faker->word,
        'percentage' => $this->faker->randomDigitNotNull,
        'DIAmountCurrency' => $this->faker->randomDigitNotNull,
        'DIAmountCurrencyER' => $this->faker->randomDigitNotNull,
        'DIAmount' => $this->faker->randomDigitNotNull,
        'localCurrency' => $this->faker->randomDigitNotNull,
        'localCurrencyER' => $this->faker->randomDigitNotNull,
        'localAmount' => $this->faker->randomDigitNotNull,
        'comRptCurrency' => $this->faker->randomDigitNotNull,
        'comRptCurrencyER' => $this->faker->randomDigitNotNull,
        'comRptAmount' => $this->faker->randomDigitNotNull,
        'budgetYear' => $this->faker->randomDigitNotNull,
        'isExtraAddon' => $this->faker->randomDigitNotNull,
        'timesReferred' => $this->faker->randomDigitNotNull,
        'timeStamp' => $this->faker->date('Y-m-d H:i:s'),
        'detail_project_id' => $this->faker->randomDigitNotNull,
        'vatMasterCategoryID' => $this->faker->randomDigitNotNull,
        'vatSubCategoryID' => $this->faker->randomDigitNotNull,
        'VATAmount' => $this->faker->randomDigitNotNull,
        'VATAmountLocal' => $this->faker->randomDigitNotNull,
        'VATAmountRpt' => $this->faker->randomDigitNotNull,
        'VATPercentage' => $this->faker->randomDigitNotNull,
        'netAmount' => $this->faker->randomDigitNotNull,
        'netAmountLocal' => $this->faker->randomDigitNotNull,
        'netAmountRpt' => $this->faker->randomDigitNotNull,
        'exempt_vat_portion' => $this->faker->randomDigitNotNull,
        'deductionType' => $this->faker->randomDigitNotNull,
        'purchaseOrderID' => $this->faker->randomDigitNotNull,
        'whtApplicable' => $this->faker->word,
        'whtAmount' => $this->faker->randomDigitNotNull,
        'whtEdited' => $this->faker->word,
        'contractID' => $this->faker->word,
        'contractDescription' => $this->faker->word
        ];
    }
}
