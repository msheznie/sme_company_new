<?php

namespace Database\Factories;

use App\Models\CMContractDeliverableAmd;
use Illuminate\Database\Eloquent\Factories\Factory;

class CMContractDeliverableAmdFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CMContractDeliverableAmd::class;

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
        'contractID' => $this->faker->randomDigitNotNull,
        'milestoneID' => $this->faker->randomDigitNotNull,
        'description' => $this->faker->word,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        'dueDate' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
