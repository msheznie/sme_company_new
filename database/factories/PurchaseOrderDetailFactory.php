<?php

namespace Database\Factories;

use App\Models\PurchaseOrderDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PurchaseOrderDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'purchaseOrderMasterID' => $this->faker->randomDigitNotNull,
        'purchaseProcessDetailID' => $this->faker->randomDigitNotNull,
        'POProcessMasterID' => $this->faker->randomDigitNotNull,
        'WO_purchaseOrderMasterID' => $this->faker->randomDigitNotNull,
        'WP_purchaseOrderDetailsID' => $this->faker->randomDigitNotNull,
        'purchaseRequestDetailsID' => $this->faker->randomDigitNotNull,
        'purchaseRequestID' => $this->faker->randomDigitNotNull,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'companyID' => $this->faker->word,
        'departmentID' => $this->faker->word,
        'serviceLineSystemID' => $this->faker->randomDigitNotNull,
        'serviceLineCode' => $this->faker->word,
        'madeLocallyYN' => $this->faker->randomDigitNotNull,
        'itemCode' => $this->faker->randomDigitNotNull,
        'itemPrimaryCode' => $this->faker->word,
        'itemDescription' => $this->faker->text,
        'itemFinanceCategoryID' => $this->faker->randomDigitNotNull,
        'itemFinanceCategorySubID' => $this->faker->randomDigitNotNull,
        'financeGLcodebBSSystemID' => $this->faker->randomDigitNotNull,
        'financeGLcodebBS' => $this->faker->word,
        'financeGLcodePLSystemID' => $this->faker->randomDigitNotNull,
        'financeGLcodePL' => $this->faker->word,
        'includePLForGRVYN' => $this->faker->randomDigitNotNull,
        'supplierPartNumber' => $this->faker->word,
        'unitOfMeasure' => $this->faker->randomDigitNotNull,
        'altUnit' => $this->faker->randomDigitNotNull,
        'altUnitValue' => $this->faker->randomDigitNotNull,
        'itemClientReferenceNumberMasterID' => $this->faker->randomDigitNotNull,
        'clientReferenceNumber' => $this->faker->word,
        'requestedQty' => $this->faker->randomDigitNotNull,
        'noQty' => $this->faker->randomDigitNotNull,
        'balanceQty' => $this->faker->randomDigitNotNull,
        'noOfDays' => $this->faker->randomDigitNotNull,
        'unitCost' => $this->faker->randomDigitNotNull,
        'discountPercentage' => $this->faker->randomDigitNotNull,
        'discountAmount' => $this->faker->randomDigitNotNull,
        'netAmount' => $this->faker->randomDigitNotNull,
        'markupPercentage' => $this->faker->randomDigitNotNull,
        'markupTransactionAmount' => $this->faker->randomDigitNotNull,
        'markupLocalAmount' => $this->faker->randomDigitNotNull,
        'markupReportingAmount' => $this->faker->randomDigitNotNull,
        'budgetYear' => $this->faker->randomDigitNotNull,
        'prBelongsYear' => $this->faker->randomDigitNotNull,
        'isAccrued' => $this->faker->randomDigitNotNull,
        'budjetAmtLocal' => $this->faker->randomDigitNotNull,
        'budjetAmtRpt' => $this->faker->randomDigitNotNull,
        'comment' => $this->faker->text,
        'supplierDefaultCurrencyID' => $this->faker->randomDigitNotNull,
        'supplierDefaultER' => $this->faker->randomDigitNotNull,
        'supplierItemCurrencyID' => $this->faker->randomDigitNotNull,
        'foreignToLocalER' => $this->faker->randomDigitNotNull,
        'companyReportingCurrencyID' => $this->faker->randomDigitNotNull,
        'companyReportingER' => $this->faker->randomDigitNotNull,
        'localCurrencyID' => $this->faker->randomDigitNotNull,
        'localCurrencyER' => $this->faker->randomDigitNotNull,
        'addonDistCost' => $this->faker->randomDigitNotNull,
        'GRVcostPerUnitLocalCur' => $this->faker->randomDigitNotNull,
        'GRVcostPerUnitSupDefaultCur' => $this->faker->randomDigitNotNull,
        'GRVcostPerUnitSupTransCur' => $this->faker->randomDigitNotNull,
        'GRVcostPerUnitComRptCur' => $this->faker->randomDigitNotNull,
        'addonPurchaseReturnCost' => $this->faker->randomDigitNotNull,
        'purchaseRetcostPerUnitLocalCur' => $this->faker->randomDigitNotNull,
        'purchaseRetcostPerUniSupDefaultCur' => $this->faker->randomDigitNotNull,
        'purchaseRetcostPerUnitTranCur' => $this->faker->randomDigitNotNull,
        'purchaseRetcostPerUnitRptCur' => $this->faker->randomDigitNotNull,
        'receivedQty' => $this->faker->randomDigitNotNull,
        'GRVSelectedYN' => $this->faker->randomDigitNotNull,
        'goodsRecievedYN' => $this->faker->randomDigitNotNull,
        'logisticSelectedYN' => $this->faker->randomDigitNotNull,
        'logisticRecievedYN' => $this->faker->randomDigitNotNull,
        'isAccruedYN' => $this->faker->randomDigitNotNull,
        'accrualJVID' => $this->faker->randomDigitNotNull,
        'timesReferred' => $this->faker->randomDigitNotNull,
        'totalWHTAmount' => $this->faker->randomDigitNotNull,
        'WHTBearedBySupplier' => $this->faker->randomDigitNotNull,
        'WHTBearedByCompany' => $this->faker->randomDigitNotNull,
        'VATPercentage' => $this->faker->randomDigitNotNull,
        'VATAmount' => $this->faker->randomDigitNotNull,
        'VATAmountLocal' => $this->faker->randomDigitNotNull,
        'VATAmountRpt' => $this->faker->randomDigitNotNull,
        'VATApplicableOn' => $this->faker->randomDigitNotNull,
        'manuallyClosed' => $this->faker->randomDigitNotNull,
        'manuallyClosedByEmpSystemID' => $this->faker->randomDigitNotNull,
        'manuallyClosedByEmpID' => $this->faker->word,
        'manuallyClosedByEmpName' => $this->faker->word,
        'manuallyClosedDate' => $this->faker->date('Y-m-d H:i:s'),
        'manuallyClosedComment' => $this->faker->text,
        'createdUserGroup' => $this->faker->word,
        'createdPcID' => $this->faker->word,
        'createdUserID' => $this->faker->word,
        'modifiedPc' => $this->faker->word,
        'modifiedUser' => $this->faker->word,
        'createdDateTime' => $this->faker->date('Y-m-d H:i:s'),
        'supplierCatalogDetailID' => $this->faker->randomDigitNotNull,
        'supplierCatalogMasterID' => $this->faker->randomDigitNotNull,
        'timeStamp' => $this->faker->date('Y-m-d H:i:s'),
        'detail_project_id' => $this->faker->randomDigitNotNull,
        'vatMasterCategoryID' => $this->faker->randomDigitNotNull,
        'vatSubCategoryID' => $this->faker->randomDigitNotNull,
        'exempt_vat_portion' => $this->faker->randomDigitNotNull,
        'contractID' => $this->faker->word,
        'contractDescription' => $this->faker->word
        ];
    }
}
