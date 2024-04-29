<?php

namespace Database\Factories;

use App\Models\ContractBoqItems;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractBoqItemsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractBoqItems::class;

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
        'itemId' => $this->faker->randomDigitNotNull,
        'description' => $this->faker->text,
        'minQty' => $this->faker->randomDigitNotNull,
        'maxQty' => $this->faker->randomDigitNotNull,
        'qty' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->word,
        'updated_by' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
