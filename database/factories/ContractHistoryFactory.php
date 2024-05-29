<?php

namespace Database\Factories;

use App\Models\ContractHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category' => $this->faker->randomDigitNotNull,
        'date' => $this->faker->word,
        'end_date' => $this->faker->word,
        'contract_id' => $this->faker->randomDigitNotNull,
        'company_id' => $this->faker->randomDigitNotNull,
        'contract_title' => $this->faker->word,
        'created_date' => $this->faker->word,
        'created_by' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
