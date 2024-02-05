<?php

namespace Database\Factories;

use App\Models\PriceList;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PriceList::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_code' => $this->faker->word,
        'item_description' => $this->faker->word,
        'part_number' => $this->faker->word,
        'uom' => $this->faker->word,
        'delivery_lead_time' => $this->faker->randomDigitNotNull,
        'currency_id' => $this->faker->randomDigitNotNull,
        'from_date' => $this->faker->randomDigitNotNull,
        'to_date' => $this->faker->randomDigitNotNull,
        'is_active' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
