<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\SupplierMasterHistory;

class SupplierMasterHistoryApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_supplier_master_history()
    {
        $supplierMasterHistory = SupplierMasterHistory::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/supplier_master_histories', $supplierMasterHistory
        );

        $this->assertApiResponse($supplierMasterHistory);
    }

    /**
     * @test
     */
    public function test_read_supplier_master_history()
    {
        $supplierMasterHistory = SupplierMasterHistory::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/supplier_master_histories/'.$supplierMasterHistory->id
        );

        $this->assertApiResponse($supplierMasterHistory->toArray());
    }

    /**
     * @test
     */
    public function test_update_supplier_master_history()
    {
        $supplierMasterHistory = SupplierMasterHistory::factory()->create();
        $editedSupplierMasterHistory = SupplierMasterHistory::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/supplier_master_histories/'.$supplierMasterHistory->id,
            $editedSupplierMasterHistory
        );

        $this->assertApiResponse($editedSupplierMasterHistory);
    }

    /**
     * @test
     */
    public function test_delete_supplier_master_history()
    {
        $supplierMasterHistory = SupplierMasterHistory::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/supplier_master_histories/'.$supplierMasterHistory->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/supplier_master_histories/'.$supplierMasterHistory->id
        );

        $this->response->assertStatus(404);
    }
}
