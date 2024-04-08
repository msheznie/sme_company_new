<?php

namespace Database\Factories;

use App\Models\ContractSettingMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractSettingMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractSettingMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'contractId' => $this->faker->randomDigitNotNull,
        'contractTypeDetailId' => $this->faker->randomDigitNotNull,
        'isActive' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
