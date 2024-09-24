<?php

namespace Database\Factories;

use App\Models\PaySupplierInvoiceDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaySupplierInvoiceDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaySupplierInvoiceDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'PayMasterAutoId' => $this->faker->randomDigitNotNull,
        'documentID' => $this->faker->word,
        'documentSystemID' => $this->faker->randomDigitNotNull,
        'apAutoID' => $this->faker->randomDigitNotNull,
        'matchingDocID' => $this->faker->randomDigitNotNull,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'companyID' => $this->faker->word,
        'addedDocumentSystemID' => $this->faker->randomDigitNotNull,
        'addedDocumentID' => $this->faker->word,
        'bookingInvSystemCode' => $this->faker->randomDigitNotNull,
        'bookingInvDocCode' => $this->faker->word,
        'bookingInvoiceDate' => $this->faker->date('Y-m-d H:i:s'),
        'addedDocumentType' => $this->faker->randomDigitNotNull,
        'supplierCodeSystem' => $this->faker->randomDigitNotNull,
        'employeeSystemID' => $this->faker->randomDigitNotNull,
        'supplierInvoiceNo' => $this->faker->word,
        'supplierInvoiceDate' => $this->faker->date('Y-m-d H:i:s'),
        'supplierTransCurrencyID' => $this->faker->randomDigitNotNull,
        'supplierTransER' => $this->faker->randomDigitNotNull,
        'supplierInvoiceAmount' => $this->faker->randomDigitNotNull,
        'supplierDefaultCurrencyID' => $this->faker->randomDigitNotNull,
        'supplierDefaultCurrencyER' => $this->faker->randomDigitNotNull,
        'supplierDefaultAmount' => $this->faker->randomDigitNotNull,
        'localCurrencyID' => $this->faker->randomDigitNotNull,
        'localER' => $this->faker->randomDigitNotNull,
        'localAmount' => $this->faker->randomDigitNotNull,
        'comRptCurrencyID' => $this->faker->randomDigitNotNull,
        'comRptER' => $this->faker->randomDigitNotNull,
        'comRptAmount' => $this->faker->randomDigitNotNull,
        'supplierPaymentCurrencyID' => $this->faker->randomDigitNotNull,
        'supplierPaymentER' => $this->faker->randomDigitNotNull,
        'supplierPaymentAmount' => $this->faker->randomDigitNotNull,
        'paymentBalancedAmount' => $this->faker->randomDigitNotNull,
        'paymentSupplierDefaultAmount' => $this->faker->randomDigitNotNull,
        'paymentLocalAmount' => $this->faker->randomDigitNotNull,
        'paymentComRptAmount' => $this->faker->randomDigitNotNull,
        'retentionVatAmount' => $this->faker->randomDigitNotNull,
        'timesReferred' => $this->faker->randomDigitNotNull,
        'isRetention' => $this->faker->word,
        'modifiedUserID' => $this->faker->word,
        'modifiedPCID' => $this->faker->word,
        'createdDateTime' => $this->faker->date('Y-m-d H:i:s'),
        'createdUserSystemID' => $this->faker->randomDigitNotNull,
        'createdUserID' => $this->faker->word,
        'createdPcID' => $this->faker->word,
        'timeStamp' => $this->faker->date('Y-m-d H:i:s'),
        'purchaseOrderID' => $this->faker->randomDigitNotNull,
        'VATAmount' => $this->faker->randomDigitNotNull,
        'VATAmountRpt' => $this->faker->randomDigitNotNull,
        'VATAmountLocal' => $this->faker->randomDigitNotNull,
        'VATPercentage' => $this->faker->randomDigitNotNull,
        'vatMasterCategoryID' => $this->faker->randomDigitNotNull,
        'vatSubCategoryID' => $this->faker->randomDigitNotNull
        ];
    }
}
