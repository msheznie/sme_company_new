<?php

namespace Database\Factories;

use App\Models\ContractMilestone;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractMilestoneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractMilestone::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->word,
        'contractID' => $this->faker->randomDigitNotNull,
        'title' => $this->faker->word,
        'percentage' => $this->faker->randomDigitNotNull,
        'amount' => $this->faker->randomDigitNotNull,
        'status' => $this->faker->word,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
