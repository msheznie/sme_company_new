<?php

namespace Database\Factories;

use App\Models\MilestoneStatusHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class MilestoneStatusHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MilestoneStatusHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->word,
        'contractID' => $this->faker->randomDigitNotNull,
        'milestoneID' => $this->faker->randomDigitNotNull,
        'changedFrom' => $this->faker->randomDigitNotNull,
        'changedTo' => $this->faker->randomDigitNotNull,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
