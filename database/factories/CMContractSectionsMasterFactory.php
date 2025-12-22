<?php

namespace Database\Factories;

use App\Models\CMContractSectionsMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class CMContractSectionsMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CMContractSectionsMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cmSection_detail' => $this->faker->word,
        'csm_active' => $this->faker->word,
        'timestamp' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
