<?php

namespace Database\Factories;

use App\Models\ContractUserAssign;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractUserAssignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractUserAssign::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->word,
        'contractId' => $this->faker->randomDigitNotNull,
        'userGroupId' => $this->faker->randomDigitNotNull,
        'userId' => $this->faker->randomDigitNotNull,
        'status' => $this->faker->randomDigitNotNull,
        'createdBy' => $this->faker->randomDigitNotNull,
        'updatedBy' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
