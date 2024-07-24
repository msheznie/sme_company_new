<?php

namespace Database\Factories;

use App\Models\ContractSectionDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractSectionDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractSectionDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sectionMasterId' => $this->faker->randomDigitNotNull,
        'description' => $this->faker->word,
        'inputType' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
