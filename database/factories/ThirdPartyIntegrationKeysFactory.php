<?php

namespace Database\Factories;

use App\Models\ThirdPartyIntegrationKeys;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThirdPartyIntegrationKeysFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ThirdPartyIntegrationKeys::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => $this->faker->randomDigitNotNull,
        'third_party_system_id' => $this->faker->randomDigitNotNull,
        'api_key' => $this->faker->word,
        'api_external_key' => $this->faker->word,
        'api_external_url' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        'expiryDate' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
