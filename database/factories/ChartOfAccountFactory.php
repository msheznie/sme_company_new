<?php

namespace Database\Factories;

use App\Models\ChartOfAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChartOfAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChartOfAccount::class;

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
        'AccountCode' => $this->faker->word,
        'AccountDescription' => $this->faker->text,
        'masterAccount' => $this->faker->word,
        'catogaryBLorPLID' => $this->faker->randomDigitNotNull,
        'catogaryBLorPL' => $this->faker->word,
        'controllAccountYN' => $this->faker->randomDigitNotNull,
        'controlAccountsSystemID' => $this->faker->randomDigitNotNull,
        'controlAccounts' => $this->faker->word,
        'isApproved' => $this->faker->randomDigitNotNull,
        'approvedBySystemID' => $this->faker->randomDigitNotNull,
        'approvedBy' => $this->faker->word,
        'approvedDate' => $this->faker->date('Y-m-d H:i:s'),
        'approvedComment' => $this->faker->text,
        'isActive' => $this->faker->randomDigitNotNull,
        'isBank' => $this->faker->randomDigitNotNull,
        'AllocationID' => $this->faker->randomDigitNotNull,
        'relatedPartyYN' => $this->faker->randomDigitNotNull,
        'interCompanySystemID' => $this->faker->randomDigitNotNull,
        'interCompanyID' => $this->faker->word,
        'confirmedYN' => $this->faker->randomDigitNotNull,
        'confirmedEmpSystemID' => $this->faker->randomDigitNotNull,
        'confirmedEmpID' => $this->faker->word,
        'confirmedEmpName' => $this->faker->word,
        'confirmedEmpDate' => $this->faker->date('Y-m-d H:i:s'),
        'isMasterAccount' => $this->faker->randomDigitNotNull,
        'RollLevForApp_curr' => $this->faker->randomDigitNotNull,
        'refferedBackYN' => $this->faker->randomDigitNotNull,
        'timesReferred' => $this->faker->randomDigitNotNull,
        'createdPcID' => $this->faker->word,
        'createdUserGroup' => $this->faker->word,
        'createdUserID' => $this->faker->word,
        'createdDateTime' => $this->faker->date('Y-m-d H:i:s'),
        'modifiedPc' => $this->faker->word,
        'modifiedUser' => $this->faker->word,
        'timestamp' => $this->faker->date('Y-m-d H:i:s'),
        'reportTemplateCategory' => $this->faker->randomDigitNotNull
        ];
    }
}
