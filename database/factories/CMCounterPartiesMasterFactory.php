<?php

namespace Database\Factories;

use App\Models\CMCounterPartiesMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class CMCounterPartiesMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CMCounterPartiesMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cmCounterParty_name' => $this->faker->word,
        'cpt_active' => $this->faker->word,
        'timestamp' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
