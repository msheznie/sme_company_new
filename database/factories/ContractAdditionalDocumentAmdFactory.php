<?php

namespace Database\Factories;

use App\Models\ContractAdditionalDocumentAmd;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractAdditionalDocumentAmdFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractAdditionalDocumentAmd::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'additional_doc_id' => $this->faker->randomDigitNotNull,
        'uuid' => $this->faker->word,
        'contractID' => $this->faker->randomDigitNotNull,
        'documentMasterID' => $this->faker->randomDigitNotNull,
        'documentType' => $this->faker->randomDigitNotNull,
        'documentName' => $this->faker->word,
        'documentDescription' => $this->faker->word,
        'expiryDate' => $this->faker->word,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
