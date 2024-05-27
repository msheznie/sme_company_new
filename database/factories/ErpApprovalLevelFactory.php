<?php

namespace Database\Factories;

use App\Models\ErpApprovalLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ErpApprovalLevelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ErpApprovalLevel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'companySystemID' => $this->faker->randomDigitNotNull,
        'companyID' => $this->faker->word,
        'departmentSystemID' => $this->faker->randomDigitNotNull,
        'departmentID' => $this->faker->word,
        'serviceLineWise' => $this->faker->randomDigitNotNull,
        'serviceLineSystemID' => $this->faker->randomDigitNotNull,
        'serviceLineCode' => $this->faker->word,
        'documentSystemID' => $this->faker->randomDigitNotNull,
        'documentID' => $this->faker->word,
        'levelDescription' => $this->faker->word,
        'noOfLevels' => $this->faker->randomDigitNotNull,
        'valueWise' => $this->faker->randomDigitNotNull,
        'valueFrom' => $this->faker->randomDigitNotNull,
        'valueTo' => $this->faker->randomDigitNotNull,
        'isCategoryWiseApproval' => $this->faker->randomDigitNotNull,
        'categoryID' => $this->faker->randomDigitNotNull,
        'isActive' => $this->faker->randomDigitNotNull,
        'is_deleted' => $this->faker->word,
        'timeStamp' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
