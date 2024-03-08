<?php

namespace Database\Factories;

use App\Models\ContractMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'contractCode' => $this->faker->word,
        'title' => $this->faker->word,
        'contractType' => $this->faker->randomDigitNotNull,
        'counterParty' => $this->faker->randomDigitNotNull,
        'counterPartyName' => $this->faker->randomDigitNotNull,
        'referenceCode' => $this->faker->word,
        'startDate' => $this->faker->date('Y-m-d H:i:s'),
        'endDate' => $this->faker->date('Y-m-d H:i:s'),
        'status' => $this->faker->randomDigitNotNull,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
