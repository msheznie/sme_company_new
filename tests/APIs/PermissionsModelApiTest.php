<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\PermissionsModel;

class PermissionsModelApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_permissions_model()
    {
        $permissionsModel = PermissionsModel::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/permissions_models', $permissionsModel
        );

        $this->assertApiResponse($permissionsModel);
    }

    /**
     * @test
     */
    public function test_read_permissions_model()
    {
        $permissionsModel = PermissionsModel::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/permissions_models/'.$permissionsModel->id
        );

        $this->assertApiResponse($permissionsModel->toArray());
    }

    /**
     * @test
     */
    public function test_update_permissions_model()
    {
        $permissionsModel = PermissionsModel::factory()->create();
        $editedPermissionsModel = PermissionsModel::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/permissions_models/'.$permissionsModel->id,
            $editedPermissionsModel
        );

        $this->assertApiResponse($editedPermissionsModel);
    }

    /**
     * @test
     */
    public function test_delete_permissions_model()
    {
        $permissionsModel = PermissionsModel::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/permissions_models/'.$permissionsModel->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/permissions_models/'.$permissionsModel->id
        );

        $this->response->assertStatus(404);
    }
}
