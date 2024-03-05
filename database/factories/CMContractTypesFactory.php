<?php

namespace Database\Factories;

use App\Models\CMContractTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class CMContractTypesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CMContractTypes::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cm_type_name' => $this->faker->word,
        'cmMaster_id' => $this->faker->randomDigitNotNull,
        'cmIntent_id' => $this->faker->randomDigitNotNull,
        'cmPartyA_id' => $this->faker->randomDigitNotNull,
        'cmPartyB_id' => $this->faker->randomDigitNotNull,
        'cmCounterParty_id' => $this->faker->randomDigitNotNull,
        'cm_type_description' => $this->faker->text,
        'ct_active' => $this->faker->word,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_by' => $this->faker->randomDigitNotNull,
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
