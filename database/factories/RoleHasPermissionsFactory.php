<?php

namespace Database\Factories;

use App\Models\RoleHasPermissions;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleHasPermissionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RoleHasPermissions::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'role_id' => $this->faker->word
        ];
    }
}
