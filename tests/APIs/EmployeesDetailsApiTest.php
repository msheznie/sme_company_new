<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\EmployeesDetails;

class EmployeesDetailsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_employees_details()
    {
        $employeesDetails = EmployeesDetails::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/employees_details', $employeesDetails
        );

        $this->assertApiResponse($employeesDetails);
    }

    /**
     * @test
     */
    public function test_read_employees_details()
    {
        $employeesDetails = EmployeesDetails::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/employees_details/'.$employeesDetails->id
        );

        $this->assertApiResponse($employeesDetails->toArray());
    }

    /**
     * @test
     */
    public function test_update_employees_details()
    {
        $employeesDetails = EmployeesDetails::factory()->create();
        $editedEmployeesDetails = EmployeesDetails::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/employees_details/'.$employeesDetails->id,
            $editedEmployeesDetails
        );

        $this->assertApiResponse($editedEmployeesDetails);
    }

    /**
     * @test
     */
    public function test_delete_employees_details()
    {
        $employeesDetails = EmployeesDetails::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/employees_details/'.$employeesDetails->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/employees_details/'.$employeesDetails->id
        );

        $this->response->assertStatus(404);
    }
}
