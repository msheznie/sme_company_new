<?php

namespace Database\Factories;

use App\Models\SupplierContactDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierContactDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SupplierContactDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'supplierID' => $this->faker->randomDigitNotNull,
        'contactTypeID' => $this->faker->randomDigitNotNull,
        'contactPersonName' => $this->faker->word,
        'contactPersonTelephone' => $this->faker->word,
        'contactPersonFax' => $this->faker->word,
        'contactPersonEmail' => $this->faker->word,
        'isDefault' => $this->faker->randomDigitNotNull,
        'timestamp' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
