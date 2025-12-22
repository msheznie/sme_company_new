<?php

namespace Database\Factories;

use App\Models\CMContractsMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class CMContractsMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CMContractsMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cmMaster_description' => $this->faker->word,
        'ctm_active' => $this->faker->word,
        'timestamp' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
