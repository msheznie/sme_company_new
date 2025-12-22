<?php

namespace Database\Factories;

use App\Models\CMIntentsMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class CMIntentsMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CMIntentsMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cmIntent_detail' => $this->faker->word,
        'cim_active' => $this->faker->word,
        'timestamp' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
