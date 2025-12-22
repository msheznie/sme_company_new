<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\AppearanceElements;

class AppearanceElementsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_appearance_elements()
    {
        $appearanceElements = AppearanceElements::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/appearance_elements', $appearanceElements
        );

        $this->assertApiResponse($appearanceElements);
    }

    /**
     * @test
     */
    public function test_read_appearance_elements()
    {
        $appearanceElements = AppearanceElements::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/appearance_elements/'.$appearanceElements->id
        );

        $this->assertApiResponse($appearanceElements->toArray());
    }

    /**
     * @test
     */
    public function test_update_appearance_elements()
    {
        $appearanceElements = AppearanceElements::factory()->create();
        $editedAppearanceElements = AppearanceElements::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/appearance_elements/'.$appearanceElements->id,
            $editedAppearanceElements
        );

        $this->assertApiResponse($editedAppearanceElements);
    }

    /**
     * @test
     */
    public function test_delete_appearance_elements()
    {
        $appearanceElements = AppearanceElements::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/appearance_elements/'.$appearanceElements->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/appearance_elements/'.$appearanceElements->id
        );

        $this->response->assertStatus(404);
    }
}
