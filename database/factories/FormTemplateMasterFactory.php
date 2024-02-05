<?php

namespace Database\Factories;

use App\Models\FormTemplateMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormTemplateMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FormTemplateMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tenant_id' => $this->faker->randomDigitNotNull,
        'name' => $this->faker->word,
        'description' => $this->faker->word,
        'status' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
