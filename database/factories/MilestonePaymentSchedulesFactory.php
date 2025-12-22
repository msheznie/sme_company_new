<?php

namespace Database\Factories;

use App\Models\MilestonePaymentSchedules;
use Illuminate\Database\Eloquent\Factories\Factory;

class MilestonePaymentSchedulesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MilestonePaymentSchedules::class;

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
        'milestone_id' => $this->faker->randomDigitNotNull,
        'description' => $this->faker->word,
        'percentage' => $this->faker->randomDigitNotNull,
        'amount' => $this->faker->randomDigitNotNull,
        'payment_due_date' => $this->faker->word,
        'actual_payment_date' => $this->faker->word,
        'milestone_due_date' => $this->faker->word,
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
