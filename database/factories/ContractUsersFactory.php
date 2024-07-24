<?php

namespace Database\Factories;

use App\Models\ContractUsers;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractUsersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractUsers::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'contractUserId' => $this->faker->randomDigitNotNull,
        'isActive' => $this->faker->randomDigitNotNull,
        'companySystemId' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
