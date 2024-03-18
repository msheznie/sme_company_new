<?php

namespace Database\Factories;

use App\Models\CustomerMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'primaryCompanySystemID' => $this->faker->randomDigitNotNull,
        'primaryCompanyID' => $this->faker->word,
        'documentSystemID' => $this->faker->randomDigitNotNull,
        'documentID' => $this->faker->word,
        'lastSerialOrder' => $this->faker->randomDigitNotNull,
        'CutomerCode' => $this->faker->word,
        'customerShortCode' => $this->faker->word,
        'customerCategoryID' => $this->faker->randomDigitNotNull,
        'custGLAccountSystemID' => $this->faker->randomDigitNotNull,
        'custGLaccount' => $this->faker->word,
        'custUnbilledAccountSystemID' => $this->faker->randomDigitNotNull,
        'custUnbilledAccount' => $this->faker->word,
        'CustomerName' => $this->faker->text,
        'customerSecondLanguage' => $this->faker->text,
        'ReportTitle' => $this->faker->text,
        'reportTitleSecondLanguage' => $this->faker->text,
        'customerAddress1' => $this->faker->text,
        'addressOneSecondLanguage' => $this->faker->text,
        'customerAddress2' => $this->faker->text,
        'addressTwoSecondLanguage' => $this->faker->text,
        'customerCity' => $this->faker->word,
        'customerCountry' => $this->faker->word,
        'CustWebsite' => $this->faker->word,
        'creditLimit' => $this->faker->randomDigitNotNull,
        'creditDays' => $this->faker->randomDigitNotNull,
        'customerLogo' => $this->faker->word,
        'interCompanyYN' => $this->faker->randomDigitNotNull,
        'companyLinkedToSystemID' => $this->faker->randomDigitNotNull,
        'companyLinkedTo' => $this->faker->word,
        'isCustomerActive' => $this->faker->randomDigitNotNull,
        'isAllowedQHSE' => $this->faker->randomDigitNotNull,
        'vendorCode' => $this->faker->word,
        'vatEligible' => $this->faker->randomDigitNotNull,
        'vatNumber' => $this->faker->word,
        'vatPercentage' => $this->faker->randomDigitNotNull,
        'isSupplierForiegn' => $this->faker->randomDigitNotNull,
        'approvedYN' => $this->faker->randomDigitNotNull,
        'approvedEmpSystemID' => $this->faker->randomDigitNotNull,
        'approvedEmpID' => $this->faker->word,
        'approvedDate' => $this->faker->date('Y-m-d H:i:s'),
        'approvedComment' => $this->faker->text,
        'confirmedYN' => $this->faker->randomDigitNotNull,
        'confirmedEmpSystemID' => $this->faker->randomDigitNotNull,
        'confirmedEmpID' => $this->faker->word,
        'confirmedEmpName' => $this->faker->word,
        'confirmedDate' => $this->faker->date('Y-m-d H:i:s'),
        'RollLevForApp_curr' => $this->faker->randomDigitNotNull,
        'refferedBackYN' => $this->faker->randomDigitNotNull,
        'timesReferred' => $this->faker->randomDigitNotNull,
        'createdUserGroup' => $this->faker->word,
        'createdUserID' => $this->faker->word,
        'createdDateTime' => $this->faker->date('Y-m-d H:i:s'),
        'createdPcID' => $this->faker->word,
        'modifiedPc' => $this->faker->word,
        'modifiedUser' => $this->faker->word,
        'timeStamp' => $this->faker->date('Y-m-d H:i:s'),
        'createdFrom' => $this->faker->randomDigitNotNull,
        'consignee_name' => $this->faker->word,
        'consignee_address' => $this->faker->text,
        'payment_terms' => $this->faker->text,
        'consignee_contact_no' => $this->faker->word,
        'customer_registration_no' => $this->faker->word,
        'customer_registration_expiry_date' => $this->faker->word,
        'custAdvanceAccountSystemID' => $this->faker->randomDigitNotNull,
        'custAdvanceAccount' => $this->faker->word
        ];
    }
}
