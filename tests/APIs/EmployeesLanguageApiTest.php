<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\EmployeesLanguage;

class EmployeesLanguageApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_employees_language()
    {
        $employeesLanguage = EmployeesLanguage::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/employees_languages', $employeesLanguage
        );

        $this->assertApiResponse($employeesLanguage);
    }

    /**
     * @test
     */
    public function test_read_employees_language()
    {
        $employeesLanguage = EmployeesLanguage::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/employees_languages/'.$employeesLanguage->id
        );

        $this->assertApiResponse($employeesLanguage->toArray());
    }

    /**
     * @test
     */
    public function test_update_employees_language()
    {
        $employeesLanguage = EmployeesLanguage::factory()->create();
        $editedEmployeesLanguage = EmployeesLanguage::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/employees_languages/'.$employeesLanguage->id,
            $editedEmployeesLanguage
        );

        $this->assertApiResponse($editedEmployeesLanguage);
    }

    /**
     * @test
     */
    public function test_delete_employees_language()
    {
        $employeesLanguage = EmployeesLanguage::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/employees_languages/'.$employeesLanguage->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/employees_languages/'.$employeesLanguage->id
        );

        $this->response->assertStatus(404);
    }
}
