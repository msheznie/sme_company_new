<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\SupplierMaster;

class SupplierMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_supplier_master()
    {
        $supplierMaster = SupplierMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/supplier_masters', $supplierMaster
        );

        $this->assertApiResponse($supplierMaster);
    }

    /**
     * @test
     */
    public function test_read_supplier_master()
    {
        $supplierMaster = SupplierMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/supplier_masters/'.$supplierMaster->id
        );

        $this->assertApiResponse($supplierMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_supplier_master()
    {
        $supplierMaster = SupplierMaster::factory()->create();
        $editedSupplierMaster = SupplierMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/supplier_masters/'.$supplierMaster->id,
            $editedSupplierMaster
        );

        $this->assertApiResponse($editedSupplierMaster);
    }

    /**
     * @test
     */
    public function test_delete_supplier_master()
    {
        $supplierMaster = SupplierMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/supplier_masters/'.$supplierMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/supplier_masters/'.$supplierMaster->id
        );

        $this->response->assertStatus(404);
    }
}
