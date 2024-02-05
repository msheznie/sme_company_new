<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Navigation;

class NavigationApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_navigation()
    {
        $navigation = Navigation::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/navigations', $navigation
        );

        $this->assertApiResponse($navigation);
    }

    /**
     * @test
     */
    public function test_read_navigation()
    {
        $navigation = Navigation::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/navigations/'.$navigation->id
        );

        $this->assertApiResponse($navigation->toArray());
    }

    /**
     * @test
     */
    public function test_update_navigation()
    {
        $navigation = Navigation::factory()->create();
        $editedNavigation = Navigation::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/navigations/'.$navigation->id,
            $editedNavigation
        );

        $this->assertApiResponse($editedNavigation);
    }

    /**
     * @test
     */
    public function test_delete_navigation()
    {
        $navigation = Navigation::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/navigations/'.$navigation->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/navigations/'.$navigation->id
        );

        $this->response->assertStatus(404);
    }
}
