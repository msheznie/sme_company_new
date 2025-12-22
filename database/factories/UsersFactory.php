<?php

namespace Database\Factories;

use App\Models\Users;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Users::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => $this->faker->randomDigitNotNull,
        'empID' => $this->faker->word,
        'name' => $this->faker->word,
        'email' => $this->faker->word,
        'username' => $this->faker->word,
        'password' => $this->faker->word,
        'remember_token' => $this->faker->word,
        'login_token' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        'uuid' => $this->faker->word
        ];
    }
}
