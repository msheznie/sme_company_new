<?php

namespace Database\Factories;

use App\Models\CMContractDocumentAmd;
use Illuminate\Database\Eloquent\Factories\Factory;

class CMContractDocumentAmdFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CMContractDocumentAmd::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'contract_doc_id' => $this->faker->randomDigitNotNull,
        'contract_history_id' => $this->faker->randomDigitNotNull,
        'uuid' => $this->faker->word,
        'contractID' => $this->faker->randomDigitNotNull,
        'documentMasterID' => $this->faker->randomDigitNotNull,
        'documentType' => $this->faker->randomDigitNotNull,
        'documentName' => $this->faker->word,
        'documentDescription' => $this->faker->word,
        'attachedDate' => $this->faker->word,
        'followingRequest' => $this->faker->word,
        'status' => $this->faker->word,
        'receivedBy' => $this->faker->word,
        'receivedDate' => $this->faker->date('Y-m-d H:i:s'),
        'receivedFormat' => $this->faker->randomDigitNotNull,
        'documentVersionNumber' => $this->faker->word,
        'documentResponsiblePerson' => $this->faker->word,
        'documentExpiryDate' => $this->faker->word,
        'returnedBy' => $this->faker->word,
        'returnedDate' => $this->faker->date('Y-m-d H:i:s'),
        'returnedTo' => $this->faker->word,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_at' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
