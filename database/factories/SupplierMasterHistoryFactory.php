<?php

namespace Database\Factories;

use App\Models\SupplierMasterHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierMasterHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SupplierMasterHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'edit_referred' => $this->faker->word,
        'ammend_comment' => $this->faker->word,
        'user_id' => $this->faker->randomDigitNotNull,
        'tenant_id' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
