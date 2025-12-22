<?php

namespace Database\Factories;

use App\Models\ContractDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractDocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractDocument::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->word,
        'documentType' => $this->faker->randomDigitNotNull,
        'documentName' => $this->faker->word,
        'documentDescription' => $this->faker->word,
        'attachedDate' => $this->faker->word,
        'followingRequest' => $this->faker->word,
        'status' => $this->faker->word,
        'receivedBy' => $this->faker->word,
        'receivedDate' => $this->faker->date('Y-m-d H:i:s'),
        'receivedFormat' => $this->faker->randomDigitNotNull,
        'documentVersionNumber' => $this->faker->randomDigitNotNull,
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
