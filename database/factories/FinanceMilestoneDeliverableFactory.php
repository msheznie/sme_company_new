<?php

namespace Database\Factories;

use App\Models\FinanceMilestoneDeliverable;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinanceMilestoneDeliverableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FinanceMilestoneDeliverable::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->word,
        'finance_document_id' => $this->faker->randomDigitNotNull,
        'document_type' => $this->faker->word,
        'document' => $this->faker->word,
        'master_id' => $this->faker->randomDigitNotNull,
        'company_id' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
