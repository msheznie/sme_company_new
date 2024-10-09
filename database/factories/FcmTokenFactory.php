<?php

namespace Database\Factories;

use App\Models\FcmToken;
use Illuminate\Database\Eloquent\Factories\Factory;

class FcmTokenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FcmToken::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'userID' => $this->faker->randomDigitNotNull,
        'fcm_token' => $this->faker->word,
        'deviceType' => $this->faker->word
        ];
    }
}
