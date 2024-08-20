<?php

namespace Database\Factories;

use App\Models\DirectPaymentDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class DirectPaymentDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DirectPaymentDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'directPaymentAutoID' => $this->faker->randomDigitNotNull,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'companyID' => $this->faker->word,
        'serviceLineSystemID' => $this->faker->randomDigitNotNull,
        'serviceLineCode' => $this->faker->word,
        'supplierID' => $this->faker->randomDigitNotNull,
        'expenseClaimMasterAutoID' => $this->faker->randomDigitNotNull,
        'chartOfAccountSystemID' => $this->faker->randomDigitNotNull,
        'glCode' => $this->faker->word,
        'glCodeDes' => $this->faker->word,
        'glCodeIsBank' => $this->faker->randomDigitNotNull,
        'comments' => $this->faker->word,
        'deductionType' => $this->faker->randomDigitNotNull,
        'supplierTransCurrencyID' => $this->faker->randomDigitNotNull,
        'supplierTransER' => $this->faker->randomDigitNotNull,
        'DPAmountCurrency' => $this->faker->randomDigitNotNull,
        'DPAmountCurrencyER' => $this->faker->randomDigitNotNull,
        'DPAmount' => $this->faker->randomDigitNotNull,
        'bankAmount' => $this->faker->randomDigitNotNull,
        'bankCurrencyID' => $this->faker->randomDigitNotNull,
        'bankCurrencyER' => $this->faker->randomDigitNotNull,
        'localCurrency' => $this->faker->randomDigitNotNull,
        'localCurrencyER' => $this->faker->randomDigitNotNull,
        'localAmount' => $this->faker->randomDigitNotNull,
        'comRptCurrency' => $this->faker->randomDigitNotNull,
        'comRptCurrencyER' => $this->faker->randomDigitNotNull,
        'comRptAmount' => $this->faker->randomDigitNotNull,
        'budgetYear' => $this->faker->randomDigitNotNull,
        'timesReferred' => $this->faker->randomDigitNotNull,
        'relatedPartyYN' => $this->faker->randomDigitNotNull,
        'pettyCashYN' => $this->faker->randomDigitNotNull,
        'glCompanySystemID' => $this->faker->randomDigitNotNull,
        'glCompanyID' => $this->faker->word,
        'vatMasterCategoryID' => $this->faker->randomDigitNotNull,
        'vatSubCategoryID' => $this->faker->randomDigitNotNull,
        'vatAmount' => $this->faker->randomDigitNotNull,
        'VATAmountLocal' => $this->faker->randomDigitNotNull,
        'VATAmountRpt' => $this->faker->randomDigitNotNull,
        'VATPercentage' => $this->faker->randomDigitNotNull,
        'netAmount' => $this->faker->randomDigitNotNull,
        'netAmountLocal' => $this->faker->randomDigitNotNull,
        'netAmountRpt' => $this->faker->randomDigitNotNull,
        'toBankID' => $this->faker->randomDigitNotNull,
        'toBankAccountID' => $this->faker->randomDigitNotNull,
        'toBankCurrencyID' => $this->faker->randomDigitNotNull,
        'toBankCurrencyER' => $this->faker->randomDigitNotNull,
        'toBankAmount' => $this->faker->randomDigitNotNull,
        'toBankGlCodeSystemID' => $this->faker->randomDigitNotNull,
        'toBankGlCode' => $this->faker->word,
        'toBankGLDescription' => $this->faker->word,
        'toCompanyLocalCurrencyID' => $this->faker->randomDigitNotNull,
        'toCompanyLocalCurrencyER' => $this->faker->randomDigitNotNull,
        'toCompanyLocalCurrencyAmount' => $this->faker->randomDigitNotNull,
        'toCompanyRptCurrencyID' => $this->faker->randomDigitNotNull,
        'toCompanyRptCurrencyER' => $this->faker->randomDigitNotNull,
        'toCompanyRptCurrencyAmount' => $this->faker->randomDigitNotNull,
        'timeStamp' => $this->faker->date('Y-m-d H:i:s'),
        'detail_project_id' => $this->faker->randomDigitNotNull,
        'contractID' => $this->faker->word,
        'contractDescription' => $this->faker->word
        ];
    }
}
