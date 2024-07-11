<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\AppearanceSettings;

class AppearanceSettingsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_appearance_settings()
    {
        $appearanceSettings = AppearanceSettings::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/appearance_settings', $appearanceSettings
        );

        $this->assertApiResponse($appearanceSettings);
    }

    /**
     * @test
     */
    public function test_read_appearance_settings()
    {
        $appearanceSettings = AppearanceSettings::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/appearance_settings/'.$appearanceSettings->id
        );

        $this->assertApiResponse($appearanceSettings->toArray());
    }

    /**
     * @test
     */
    public function test_update_appearance_settings()
    {
        $appearanceSettings = AppearanceSettings::factory()->create();
        $editedAppearanceSettings = AppearanceSettings::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/appearance_settings/'.$appearanceSettings->id,
            $editedAppearanceSettings
        );

        $this->assertApiResponse($editedAppearanceSettings);
    }

    /**
     * @test
     */
    public function test_delete_appearance_settings()
    {
        $appearanceSettings = AppearanceSettings::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/appearance_settings/'.$appearanceSettings->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/appearance_settings/'.$appearanceSettings->id
        );

        $this->response->assertStatus(404);
    }
}
