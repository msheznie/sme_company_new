<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\NavigationRole;

class NavigationRoleApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_navigation_role()
    {
        $navigationRole = NavigationRole::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/navigation_roles', $navigationRole
        );

        $this->assertApiResponse($navigationRole);
    }

    /**
     * @test
     */
    public function test_read_navigation_role()
    {
        $navigationRole = NavigationRole::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/navigation_roles/'.$navigationRole->id
        );

        $this->assertApiResponse($navigationRole->toArray());
    }

    /**
     * @test
     */
    public function test_update_navigation_role()
    {
        $navigationRole = NavigationRole::factory()->create();
        $editedNavigationRole = NavigationRole::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/navigation_roles/'.$navigationRole->id,
            $editedNavigationRole
        );

        $this->assertApiResponse($editedNavigationRole);
    }

    /**
     * @test
     */
    public function test_delete_navigation_role()
    {
        $navigationRole = NavigationRole::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/navigation_roles/'.$navigationRole->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/navigation_roles/'.$navigationRole->id
        );

        $this->response->assertStatus(404);
    }
}
