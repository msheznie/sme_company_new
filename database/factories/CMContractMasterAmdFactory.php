<?php

namespace Database\Factories;

use App\Models\CMContractMasterAmd;
use Illuminate\Database\Eloquent\Factories\Factory;

class CMContractMasterAmdFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CMContractMasterAmd::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'contract_history_id' => $this->faker->randomDigitNotNull,
        'uuid' => $this->faker->word,
        'contractCode' => $this->faker->word,
        'title' => $this->faker->word,
        'description' => $this->faker->text,
        'contractType' => $this->faker->randomDigitNotNull,
        'counterParty' => $this->faker->randomDigitNotNull,
        'counterPartyName' => $this->faker->randomDigitNotNull,
        'referenceCode' => $this->faker->word,
        'contractOwner' => $this->faker->randomDigitNotNull,
        'contractAmount' => $this->faker->randomDigitNotNull,
        'startDate' => $this->faker->date('Y-m-d H:i:s'),
        'endDate' => $this->faker->date('Y-m-d H:i:s'),
        'agreementSignDate' => $this->faker->date('Y-m-d H:i:s'),
        'notifyDays' => $this->faker->randomDigitNotNull,
        'contractTermPeriod' => $this->faker->word,
        'contractRenewalDate' => $this->faker->date('Y-m-d H:i:s'),
        'contractExtensionDate' => $this->faker->date('Y-m-d H:i:s'),
        'contractTerminateDate' => $this->faker->date('Y-m-d H:i:s'),
        'contractRevisionDate' => $this->faker->date('Y-m-d H:i:s'),
        'primaryCounterParty' => $this->faker->word,
        'primaryEmail' => $this->faker->word,
        'primaryPhoneNumber' => $this->faker->word,
        'secondaryCounterParty' => $this->faker->word,
        'secondaryEmail' => $this->faker->word,
        'secondaryPhoneNumber' => $this->faker->word,
        'documentMasterId' => $this->faker->randomDigitNotNull,
        'status' => $this->faker->randomDigitNotNull,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'confirmed_yn' => $this->faker->randomDigitNotNull,
        'confirmed_date' => $this->faker->date('Y-m-d H:i:s'),
        'confirm_by' => $this->faker->randomDigitNotNull,
        'confirmed_comment' => $this->faker->word,
        'rollLevelOrder' => $this->faker->randomDigitNotNull,
        'refferedBackYN' => $this->faker->randomDigitNotNull,
        'timesReferred' => $this->faker->randomDigitNotNull,
        'approved_yn' => $this->faker->randomDigitNotNull,
        'approved_by' => $this->faker->randomDigitNotNull,
        'approved_date' => $this->faker->date('Y-m-d H:i:s'),
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        'is_amendment' => $this->faker->randomDigitNotNull,
        'is_addendum' => $this->faker->randomDigitNotNull,
        'is_renewal' => $this->faker->randomDigitNotNull,
        'is_extension' => $this->faker->randomDigitNotNull,
        'is_revision' => $this->faker->randomDigitNotNull,
        'is_termination' => $this->faker->randomDigitNotNull,
        'parent_id' => $this->faker->randomDigitNotNull,
        'tender_id' => $this->faker->randomDigitNotNull
        ];
    }
}
