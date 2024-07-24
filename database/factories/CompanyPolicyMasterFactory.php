<?php

namespace Database\Factories;

use App\Models\CompanyPolicyMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyPolicyMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyPolicyMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'companyPolicyCategoryID' => $this->faker->randomDigitNotNull,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'companyID' => $this->faker->word,
        'documentID' => $this->faker->word,
        'isYesNO' => $this->faker->randomDigitNotNull,
        'policyValue' => $this->faker->randomDigitNotNull,
        'createdByUserID' => $this->faker->word,
        'createdByUserName' => $this->faker->word,
        'createdByPCID' => $this->faker->word,
        'modifiedByUserID' => $this->faker->word,
        'createdDateTime' => $this->faker->date('Y-m-d H:i:s'),
        'timestamp' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
