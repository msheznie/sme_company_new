<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\SupplierAssigned;

class SupplierAssignedApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_supplier_assigned()
    {
        $supplierAssigned = SupplierAssigned::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/supplier_assigneds', $supplierAssigned
        );

        $this->assertApiResponse($supplierAssigned);
    }

    /**
     * @test
     */
    public function test_read_supplier_assigned()
    {
        $supplierAssigned = SupplierAssigned::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/supplier_assigneds/'.$supplierAssigned->id
        );

        $this->assertApiResponse($supplierAssigned->toArray());
    }

    /**
     * @test
     */
    public function test_update_supplier_assigned()
    {
        $supplierAssigned = SupplierAssigned::factory()->create();
        $editedSupplierAssigned = SupplierAssigned::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/supplier_assigneds/'.$supplierAssigned->id,
            $editedSupplierAssigned
        );

        $this->assertApiResponse($editedSupplierAssigned);
    }

    /**
     * @test
     */
    public function test_delete_supplier_assigned()
    {
        $supplierAssigned = SupplierAssigned::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/supplier_assigneds/'.$supplierAssigned->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/supplier_assigneds/'.$supplierAssigned->id
        );

        $this->response->assertStatus(404);
    }
}
