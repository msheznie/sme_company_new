<?php

namespace Database\Factories;

use App\Models\ErpDocumentAttachments;
use Illuminate\Database\Eloquent\Factories\Factory;

class ErpDocumentAttachmentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ErpDocumentAttachments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'companySystemID' => $this->faker->randomDigitNotNull,
        'companyID' => $this->faker->word,
        'documentSystemID' => $this->faker->randomDigitNotNull,
        'documentID' => $this->faker->word,
        'documentSystemCode' => $this->faker->randomDigitNotNull,
        'approvalLevelOrder' => $this->faker->randomDigitNotNull,
        'attachmentDescription' => $this->faker->text,
        'location' => $this->faker->word,
        'path' => $this->faker->word,
        'originalFileName' => $this->faker->word,
        'myFileName' => $this->faker->word,
        'docExpirtyDate' => $this->faker->date('Y-m-d H:i:s'),
        'attachmentType' => $this->faker->randomDigitNotNull,
        'sizeInKbs' => $this->faker->randomDigitNotNull,
        'isUploaded' => $this->faker->randomDigitNotNull,
        'pullFromAnotherDocument' => $this->faker->randomDigitNotNull,
        'parent_id' => $this->faker->randomDigitNotNull,
        'timeStamp' => $this->faker->date('Y-m-d H:i:s'),
        'envelopType' => $this->faker->randomDigitNotNull,
        'order_number' => $this->faker->randomDigitNotNull
        ];
    }
}
