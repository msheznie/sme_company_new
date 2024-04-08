<?php

namespace Database\Factories;

use App\Models\ContractSettingDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractSettingDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractSettingDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'settingMasterId' => $this->faker->randomDigitNotNull,
        'sectionDetailId' => $this->faker->randomDigitNotNull,
        'isActive' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
