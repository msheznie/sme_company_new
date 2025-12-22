<?php

namespace Database\Factories;

use App\Models\ErpDocumentApproved;
use Illuminate\Database\Eloquent\Factories\Factory;

class ErpDocumentApprovedFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ErpDocumentApproved::class;

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
        'departmentSystemID' => $this->faker->randomDigitNotNull,
        'departmentID' => $this->faker->word,
        'serviceLineSystemID' => $this->faker->randomDigitNotNull,
        'serviceLineCode' => $this->faker->word,
        'documentSystemID' => $this->faker->randomDigitNotNull,
        'documentID' => $this->faker->word,
        'documentSystemCode' => $this->faker->randomDigitNotNull,
        'documentCode' => $this->faker->word,
        'documentDate' => $this->faker->date('Y-m-d H:i:s'),
        'approvalLevelID' => $this->faker->randomDigitNotNull,
        'rollID' => $this->faker->randomDigitNotNull,
        'approvalGroupID' => $this->faker->randomDigitNotNull,
        'rollLevelOrder' => $this->faker->randomDigitNotNull,
        'employeeSystemID' => $this->faker->randomDigitNotNull,
        'employeeID' => $this->faker->word,
        'docConfirmedDate' => $this->faker->date('Y-m-d H:i:s'),
        'docConfirmedByEmpSystemID' => $this->faker->randomDigitNotNull,
        'docConfirmedByEmpID' => $this->faker->word,
        'preRollApprovedDate' => $this->faker->date('Y-m-d H:i:s'),
        'approvedYN' => $this->faker->randomDigitNotNull,
        'approvedDate' => $this->faker->date('Y-m-d H:i:s'),
        'approvedComments' => $this->faker->text,
        'rejectedYN' => $this->faker->randomDigitNotNull,
        'rejectedDate' => $this->faker->date('Y-m-d H:i:s'),
        'rejectedComments' => $this->faker->text,
        'myApproveFlag' => $this->faker->randomDigitNotNull,
        'isDeligationApproval' => $this->faker->randomDigitNotNull,
        'approvedForEmpID' => $this->faker->word,
        'isApprovedFromPC' => $this->faker->randomDigitNotNull,
        'approvedPCID' => $this->faker->word,
        'reference_email' => $this->faker->word,
        'timeStamp' => $this->faker->date('Y-m-d H:i:s'),
        'status' => $this->faker->word
        ];
    }
}
