<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\SupplierDetailHistory;

class SupplierDetailHistoryApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_supplier_detail_history()
    {
        $supplierDetailHistory = SupplierDetailHistory::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/supplier_detail_histories', $supplierDetailHistory
        );

        $this->assertApiResponse($supplierDetailHistory);
    }

    /**
     * @test
     */
    public function test_read_supplier_detail_history()
    {
        $supplierDetailHistory = SupplierDetailHistory::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/supplier_detail_histories/'.$supplierDetailHistory->id
        );

        $this->assertApiResponse($supplierDetailHistory->toArray());
    }

    /**
     * @test
     */
    public function test_update_supplier_detail_history()
    {
        $supplierDetailHistory = SupplierDetailHistory::factory()->create();
        $editedSupplierDetailHistory = SupplierDetailHistory::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/supplier_detail_histories/'.$supplierDetailHistory->id,
            $editedSupplierDetailHistory
        );

        $this->assertApiResponse($editedSupplierDetailHistory);
    }

    /**
     * @test
     */
    public function test_delete_supplier_detail_history()
    {
        $supplierDetailHistory = SupplierDetailHistory::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/supplier_detail_histories/'.$supplierDetailHistory->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/supplier_detail_histories/'.$supplierDetailHistory->id
        );

        $this->response->assertStatus(404);
    }
}
