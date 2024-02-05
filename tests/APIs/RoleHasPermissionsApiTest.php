<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\RoleHasPermissions;

class RoleHasPermissionsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_role_has_permissions()
    {
        $roleHasPermissions = RoleHasPermissions::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/role_has_permissions', $roleHasPermissions
        );

        $this->assertApiResponse($roleHasPermissions);
    }

    /**
     * @test
     */
    public function test_read_role_has_permissions()
    {
        $roleHasPermissions = RoleHasPermissions::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/role_has_permissions/'.$roleHasPermissions->id
        );

        $this->assertApiResponse($roleHasPermissions->toArray());
    }

    /**
     * @test
     */
    public function test_update_role_has_permissions()
    {
        $roleHasPermissions = RoleHasPermissions::factory()->create();
        $editedRoleHasPermissions = RoleHasPermissions::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/role_has_permissions/'.$roleHasPermissions->id,
            $editedRoleHasPermissions
        );

        $this->assertApiResponse($editedRoleHasPermissions);
    }

    /**
     * @test
     */
    public function test_delete_role_has_permissions()
    {
        $roleHasPermissions = RoleHasPermissions::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/role_has_permissions/'.$roleHasPermissions->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/role_has_permissions/'.$roleHasPermissions->id
        );

        $this->response->assertStatus(404);
    }
}
