<?php

namespace Database\Factories;

use App\Models\DepartmentMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DepartmentMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'DepartmentDes' => $this->faker->word,
        'parent_department_id' => $this->faker->randomDigitNotNull,
        'is_root_department' => $this->faker->randomDigitNotNull,
        'Erp_companyID' => $this->faker->randomDigitNotNull,
        'SchMasterID' => $this->faker->randomDigitNotNull,
        'BranchID' => $this->faker->randomDigitNotNull,
        'SortOrder' => $this->faker->randomDigitNotNull,
        'hod_id' => $this->faker->randomDigitNotNull,
        'isActive' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'CreatedUserName' => $this->faker->word,
        'CreatedDate' => $this->faker->date('Y-m-d H:i:s'),
        'CreatedPC' => $this->faker->word,
        'ModifiedUserName' => $this->faker->word,
        'Timestamp' => $this->faker->date('Y-m-d H:i:s'),
        'ModifiedPC' => $this->faker->word
        ];
    }
}
