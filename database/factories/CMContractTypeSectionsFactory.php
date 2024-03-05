<?php

namespace Database\Factories;

use App\Models\CMContractTypeSections;
use Illuminate\Database\Eloquent\Factories\Factory;

class CMContractTypeSectionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CMContractTypeSections::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'contract_typeId' => $this->faker->randomDigitNotNull,
        'cmSection_id' => $this->faker->randomDigitNotNull,
        'is_enabled' => $this->faker->word,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_by' => $this->faker->randomDigitNotNull,
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
