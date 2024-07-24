<?php

namespace Database\Factories;

use App\Models\CMContractBoqItemsAmd;
use Illuminate\Database\Eloquent\Factories\Factory;

class CMContractBoqItemsAmdFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CMContractBoqItemsAmd::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->word,
        'contract_history_id' => $this->faker->randomDigitNotNull,
        'uuid' => $this->faker->word,
        'contractId' => $this->faker->randomDigitNotNull,
        'companyId' => $this->faker->randomDigitNotNull,
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
