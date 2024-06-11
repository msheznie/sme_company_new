<?php

namespace Database\Factories;

use App\Models\ErpEmployeesDepartments;
use Illuminate\Database\Eloquent\Factories\Factory;

class ErpEmployeesDepartmentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ErpEmployeesDepartments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $format = 'Y-m-d H:i:s';
        return [
            'employeeSystemID' => $this->faker->randomDigitNotNull,
        'employeeID' => $this->faker->word,
        'employeeGroupID' => $this->faker->randomDigitNotNull,
        'companySystemID' => $this->faker->randomDigitNotNull,
        'companyId' => $this->faker->word,
        'documentSystemID' => $this->faker->randomDigitNotNull,
        'documentID' => $this->faker->word,
        'departmentSystemID' => $this->faker->randomDigitNotNull,
        'departmentID' => $this->faker->word,
        'ServiceLineSystemID' => $this->faker->randomDigitNotNull,
        'ServiceLineID' => $this->faker->word,
        'warehouseSystemCode' => $this->faker->randomDigitNotNull,
        'reportingManagerID' => $this->faker->word,
        'isDefault' => $this->faker->randomDigitNotNull,
        'dischargedYN' => $this->faker->randomDigitNotNull,
        'approvalDeligated' => $this->faker->randomDigitNotNull,
        'approvalDeligatedFromEmpID' => $this->faker->word,
        'approvalDeligatedFrom' => $this->faker->word,
        'approvalDeligatedTo' => $this->faker->word,
        'dmsIsUploadEnable' => $this->faker->randomDigitNotNull,
        'isActive' => $this->faker->randomDigitNotNull,
        'activatedDate' => $this->faker->date($format),
        'activatedByEmpID' => $this->faker->word,
        'activatedByEmpSystemID' => $this->faker->randomDigitNotNull,
        'removedYN' => $this->faker->randomDigitNotNull,
        'removedByEmpID' => $this->faker->word,
        'removedByEmpSystemID' => $this->faker->randomDigitNotNull,
        'removedDate' => $this->faker->date($format),
        'createdDate' => $this->faker->date($format),
        'createdByEmpSystemID' => $this->faker->randomDigitNotNull,
        'timeStamp' => $this->faker->date($format),
        'deligateDetaileID' => $this->faker->randomDigitNotNull
        ];
    }
}
