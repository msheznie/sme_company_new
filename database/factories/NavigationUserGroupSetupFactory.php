<?php

namespace Database\Factories;

use App\Models\NavigationUserGroupSetup;
use Illuminate\Database\Eloquent\Factories\Factory;

class NavigationUserGroupSetupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NavigationUserGroupSetup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'userGroupID' => $this->faker->randomDigitNotNull,
        'companyID' => $this->faker->randomDigitNotNull,
        'navigationMenuID' => $this->faker->randomDigitNotNull,
        'description' => $this->faker->word,
        'masterID' => $this->faker->randomDigitNotNull,
        'url' => $this->faker->text,
        'pageID' => $this->faker->word,
        'pageTitle' => $this->faker->word,
        'pageIcon' => $this->faker->word,
        'levelNo' => $this->faker->randomDigitNotNull,
        'sortOrder' => $this->faker->randomDigitNotNull,
        'isSubExist' => $this->faker->randomDigitNotNull,
        'readonly' => $this->faker->word,
        'create' => $this->faker->word,
        'update' => $this->faker->word,
        'delete' => $this->faker->word,
        'print' => $this->faker->word,
        'export' => $this->faker->word,
        'timestamp' => $this->faker->date('Y-m-d H:i:s'),
        'isPortalYN' => $this->faker->randomDigitNotNull,
        'externalLink' => $this->faker->text
        ];
    }
}
