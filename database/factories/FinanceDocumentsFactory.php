<?php

namespace Database\Factories;

use App\Models\FinanceDocuments;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinanceDocumentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FinanceDocuments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->word,
        'contract_id' => $this->faker->randomDigitNotNull,
        'document_type' => $this->faker->word,
        'document_id' => $this->faker->randomDigitNotNull,
        'document_system_id' => $this->faker->randomDigitNotNull,
        'company_id' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
