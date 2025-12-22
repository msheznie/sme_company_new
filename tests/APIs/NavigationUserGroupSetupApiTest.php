<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\NavigationUserGroupSetup;

class NavigationUserGroupSetupApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_navigation_user_group_setup()
    {
        $navigationUserGroupSetup = NavigationUserGroupSetup::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/navigation_user_group_setups', $navigationUserGroupSetup
        );

        $this->assertApiResponse($navigationUserGroupSetup);
    }

    /**
     * @test
     */
    public function test_read_navigation_user_group_setup()
    {
        $navigationUserGroupSetup = NavigationUserGroupSetup::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/navigation_user_group_setups/'.$navigationUserGroupSetup->id
        );

        $this->assertApiResponse($navigationUserGroupSetup->toArray());
    }

    /**
     * @test
     */
    public function test_update_navigation_user_group_setup()
    {
        $navigationUserGroupSetup = NavigationUserGroupSetup::factory()->create();
        $editedNavigationUserGroupSetup = NavigationUserGroupSetup::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/navigation_user_group_setups/'.$navigationUserGroupSetup->id,
            $editedNavigationUserGroupSetup
        );

        $this->assertApiResponse($editedNavigationUserGroupSetup);
    }

    /**
     * @test
     */
    public function test_delete_navigation_user_group_setup()
    {
        $navigationUserGroupSetup = NavigationUserGroupSetup::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/navigation_user_group_setups/'.$navigationUserGroupSetup->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/navigation_user_group_setups/'.$navigationUserGroupSetup->id
        );

        $this->response->assertStatus(404);
    }
}
