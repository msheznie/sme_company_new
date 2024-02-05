<?php

namespace Database\Factories;

use App\Models\SupplierDetailHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierDetailHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SupplierDetailHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomDigitNotNull,
        'tenant_id' => $this->faker->randomDigitNotNull,
        'form_section_id' => $this->faker->randomDigitNotNull,
        'form_group_id' => $this->faker->randomDigitNotNull,
        'form_field_id' => $this->faker->randomDigitNotNull,
        'form_data_id' => $this->faker->randomDigitNotNull,
        'sort' => $this->faker->randomDigitNotNull,
        'value' => $this->faker->text,
        'status' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        'master_id' => $this->faker->word
        ];
    }
}
