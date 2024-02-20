<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ErpEmployeeNavigation;

class ErpEmployeeNavigationApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_erp_employee_navigation()
    {
        $erpEmployeeNavigation = ErpEmployeeNavigation::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/erp_employee_navigations', $erpEmployeeNavigation
        );

        $this->assertApiResponse($erpEmployeeNavigation);
    }

    /**
     * @test
     */
    public function test_read_erp_employee_navigation()
    {
        $erpEmployeeNavigation = ErpEmployeeNavigation::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/erp_employee_navigations/'.$erpEmployeeNavigation->id
        );

        $this->assertApiResponse($erpEmployeeNavigation->toArray());
    }

    /**
     * @test
     */
    public function test_update_erp_employee_navigation()
    {
        $erpEmployeeNavigation = ErpEmployeeNavigation::factory()->create();
        $editedErpEmployeeNavigation = ErpEmployeeNavigation::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/erp_employee_navigations/'.$erpEmployeeNavigation->id,
            $editedErpEmployeeNavigation
        );

        $this->assertApiResponse($editedErpEmployeeNavigation);
    }

    /**
     * @test
     */
    public function test_delete_erp_employee_navigation()
    {
        $erpEmployeeNavigation = ErpEmployeeNavigation::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/erp_employee_navigations/'.$erpEmployeeNavigation->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/erp_employee_navigations/'.$erpEmployeeNavigation->id
        );

        $this->response->assertStatus(404);
    }
}
