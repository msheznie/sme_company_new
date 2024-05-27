<?php

namespace Database\Factories;

use App\Models\CompanyDocumentAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyDocumentAttachmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyDocumentAttachment::class;

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
        'docRefNumber' => $this->faker->word,
        'isAttachmentYN' => $this->faker->randomDigitNotNull,
        'sendEmailYN' => $this->faker->randomDigitNotNull,
        'codeGeneratorFormat' => $this->faker->word,
        'isAmountApproval' => $this->faker->randomDigitNotNull,
        'isServiceLineAccess' => $this->faker->randomDigitNotNull,
        'isServiceLineApproval' => $this->faker->randomDigitNotNull,
        'isCategoryApproval' => $this->faker->randomDigitNotNull,
        'blockYN' => $this->faker->randomDigitNotNull,
        'enableAttachmentAfterApproval' => $this->faker->randomDigitNotNull,
        'timeStamp' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
