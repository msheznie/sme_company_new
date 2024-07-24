<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ErpEmployeesDepartments;

class ErpEmployeesDepartmentsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;
    protected $url = '/api/erp_employees_departments';

    /**
     * @test
     */
    public function test_create_erp_employees_departments()
    {
        $erpEmployeesDepartments = ErpEmployeesDepartments::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            $this->url, $erpEmployeesDepartments
        );

        $this->assertApiResponse($erpEmployeesDepartments);
    }

    /**
     * @test
     */
    public function test_read_erp_employees_departments()
    {
        $erpEmployeesDepartments = ErpEmployeesDepartments::factory()->create();

        $this->response = $this->json(
            'GET',
            $this->url.$erpEmployeesDepartments->id
        );

        $this->assertApiResponse($erpEmployeesDepartments->toArray());
    }

    /**
     * @test
     */
    public function test_update_erp_employees_departments()
    {
        $erpEmployeesDepartments = ErpEmployeesDepartments::factory()->create();
        $editedErpEmployeesDepartments = ErpEmployeesDepartments::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            $this->url.$erpEmployeesDepartments->id,
            $editedErpEmployeesDepartments
        );

        $this->assertApiResponse($editedErpEmployeesDepartments);
    }

    /**
     * @test
     */
    public function test_delete_erp_employees_departments()
    {
        $erpEmployeesDepartments = ErpEmployeesDepartments::factory()->create();

        $this->response = $this->json(
            'DELETE',
            $this->url.$erpEmployeesDepartments->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            $this->url.$erpEmployeesDepartments->id
        );

        $this->response->assertStatus(404);
    }
}
