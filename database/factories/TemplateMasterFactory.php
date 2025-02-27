<?php

namespace Database\Factories;

use App\Models\TemplateMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TemplateMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->randomDigitNotNull,
        'uuid' => $this->faker->word,
        'contract_id' => $this->faker->randomDigitNotNull,
        'content' => $this->faker->text,
        'company_id' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
