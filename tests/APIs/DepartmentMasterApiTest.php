<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\DepartmentMaster;

class DepartmentMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_department_master()
    {
        $departmentMaster = DepartmentMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/department_masters', $departmentMaster
        );

        $this->assertApiResponse($departmentMaster);
    }

    /**
     * @test
     */
    public function test_read_department_master()
    {
        $departmentMaster = DepartmentMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/department_masters/'.$departmentMaster->id
        );

        $this->assertApiResponse($departmentMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_department_master()
    {
        $departmentMaster = DepartmentMaster::factory()->create();
        $editedDepartmentMaster = DepartmentMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/department_masters/'.$departmentMaster->id,
            $editedDepartmentMaster
        );

        $this->assertApiResponse($editedDepartmentMaster);
    }

    /**
     * @test
     */
    public function test_delete_department_master()
    {
        $departmentMaster = DepartmentMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/department_masters/'.$departmentMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/department_masters/'.$departmentMaster->id
        );

        $this->response->assertStatus(404);
    }
}
