<?php

namespace Database\Factories;

use App\Models\Alert;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlertFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Alert::class;
    protected $dateFormat = 'Y-m-d H:i:s';

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
        'empSystemID' => $this->faker->randomDigitNotNull,
        'empID' => $this->faker->word,
        'docSystemID' => $this->faker->randomDigitNotNull,
        'docID' => $this->faker->word,
        'docApprovedYN' => $this->faker->randomDigitNotNull,
        'docSystemCode' => $this->faker->randomDigitNotNull,
        'docCode' => $this->faker->word,
        'alertMessage' => $this->faker->text,
        'alertDateTime' => $this->faker->date($this->dateFormat),
        'alertViewedYN' => $this->faker->randomDigitNotNull,
        'alertViewedDateTime' => $this->faker->date($this->dateFormat),
        'empName' => $this->faker->word,
        'empEmail' => $this->faker->word,
        'ccEmailID' => $this->faker->word,
        'emailAlertMessage' => $this->faker->text,
        'isEmailSend' => $this->faker->randomDigitNotNull,
        'attachmentFileName' => $this->faker->word,
        'timeStamp' => $this->faker->date($this->dateFormat)
        ];
    }
}
