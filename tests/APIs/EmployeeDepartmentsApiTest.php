<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\EmployeeDepartments;

class EmployeeDepartmentsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_employee_departments()
    {
        $employeeDepartments = EmployeeDepartments::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/employee_departments', $employeeDepartments
        );

        $this->assertApiResponse($employeeDepartments);
    }

    /**
     * @test
     */
    public function test_read_employee_departments()
    {
        $employeeDepartments = EmployeeDepartments::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/employee_departments/'.$employeeDepartments->id
        );

        $this->assertApiResponse($employeeDepartments->toArray());
    }

    /**
     * @test
     */
    public function test_update_employee_departments()
    {
        $employeeDepartments = EmployeeDepartments::factory()->create();
        $editedEmployeeDepartments = EmployeeDepartments::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/employee_departments/'.$employeeDepartments->id,
            $editedEmployeeDepartments
        );

        $this->assertApiResponse($editedEmployeeDepartments);
    }

    /**
     * @test
     */
    public function test_delete_employee_departments()
    {
        $employeeDepartments = EmployeeDepartments::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/employee_departments/'.$employeeDepartments->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/employee_departments/'.$employeeDepartments->id
        );

        $this->response->assertStatus(404);
    }
}
