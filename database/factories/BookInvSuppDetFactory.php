<?php

namespace Database\Factories;

use App\Models\BookInvSuppDet;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookInvSuppDetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BookInvSuppDet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'bookingSuppMasInvAutoID' => $this->faker->randomDigitNotNull,
        'unbilledgrvAutoID' => $this->faker->randomDigitNotNull,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'companyID' => $this->faker->word,
        'supplierID' => $this->faker->randomDigitNotNull,
        'purchaseOrderID' => $this->faker->randomDigitNotNull,
        'grvAutoID' => $this->faker->randomDigitNotNull,
        'grvType' => $this->faker->word,
        'supplierTransactionCurrencyID' => $this->faker->randomDigitNotNull,
        'supplierTransactionCurrencyER' => $this->faker->randomDigitNotNull,
        'companyReportingCurrencyID' => $this->faker->randomDigitNotNull,
        'companyReportingER' => $this->faker->randomDigitNotNull,
        'localCurrencyID' => $this->faker->randomDigitNotNull,
        'localCurrencyER' => $this->faker->randomDigitNotNull,
        'supplierInvoOrderedAmount' => $this->faker->randomDigitNotNull,
        'supplierInvoAmount' => $this->faker->randomDigitNotNull,
        'transSupplierInvoAmount' => $this->faker->randomDigitNotNull,
        'localSupplierInvoAmount' => $this->faker->randomDigitNotNull,
        'rptSupplierInvoAmount' => $this->faker->randomDigitNotNull,
        'totTransactionAmount' => $this->faker->randomDigitNotNull,
        'totLocalAmount' => $this->faker->randomDigitNotNull,
        'totRptAmount' => $this->faker->randomDigitNotNull,
        'VATAmount' => $this->faker->randomDigitNotNull,
        'VATAmountLocal' => $this->faker->randomDigitNotNull,
        'VATAmountRpt' => $this->faker->randomDigitNotNull,
        'isAddon' => $this->faker->randomDigitNotNull,
        'invoiceBeforeGRVYN' => $this->faker->randomDigitNotNull,
        'timesReferred' => $this->faker->randomDigitNotNull,
        'timeStamp' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
