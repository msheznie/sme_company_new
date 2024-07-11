<?php

namespace Database\Factories;

use App\Models\PeriodicBillings;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeriodicBillingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PeriodicBillings::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $dateFormat = 'Y-m-d H:i:s';
        return [
            'uuid' => $this->faker->word,
        'contract_id' => $this->faker->randomDigitNotNull,
        'amount' => $this->faker->randomDigitNotNull,
        'start_date' => $this->faker->date($dateFormat),
        'end_date' => $this->faker->date($dateFormat),
        'occurrence_type' => $this->faker->word,
        'due_in' => $this->faker->randomDigitNotNull,
        'no_of_installment' => $this->faker->randomDigitNotNull,
        'inst_payment_amount' => $this->faker->randomDigitNotNull,
        'currency_id' => $this->faker->word,
        'company_id' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date($dateFormat),
        'created_at' => $this->faker->date($dateFormat),
        'updated_at' => $this->faker->date($dateFormat)
        ];
    }
}
