<?php

namespace Database\Factories;

use App\Models\ErpDocumentMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class ErpDocumentMasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ErpDocumentMaster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'documentID' => $this->faker->word,
        'documentDescription' => $this->faker->word,
        'departmentSystemID' => $this->faker->randomDigitNotNull,
        'departmentID' => $this->faker->word,
        'isPrintable' => $this->faker->word,
        'timeStamp' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
