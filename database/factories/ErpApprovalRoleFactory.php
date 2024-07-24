<?php

namespace Database\Factories;

use App\Models\ErpApprovalRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class ErpApprovalRoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ErpApprovalRole::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'rollDescription' => $this->faker->word,
        'documentSystemID' => $this->faker->randomDigitNotNull,
        'documentID' => $this->faker->word,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'companyID' => $this->faker->word,
        'departmentSystemID' => $this->faker->randomDigitNotNull,
        'departmentID' => $this->faker->word,
        'serviceLineSystemID' => $this->faker->randomDigitNotNull,
        'serviceLineID' => $this->faker->word,
        'rollLevel' => $this->faker->randomDigitNotNull,
        'approvalLevelID' => $this->faker->randomDigitNotNull,
        'approvalGroupID' => $this->faker->randomDigitNotNull,
        'timeStamp' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
