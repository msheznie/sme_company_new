<?php

namespace Database\Factories;

use App\Models\CMPartiesMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class CMPartiesMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CMPartiesMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cmParty_name' => $this->faker->word,
        'cpm_active' => $this->faker->word,
        'timestamp' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
