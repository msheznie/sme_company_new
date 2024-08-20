<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\PurchaseOrderMaster;

class PurchaseOrderMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_purchase_order_master()
    {
        $purchaseOrderMaster = PurchaseOrderMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/purchase_order_masters', $purchaseOrderMaster
        );

        $this->assertApiResponse($purchaseOrderMaster);
    }

    /**
     * @test
     */
    public function test_read_purchase_order_master()
    {
        $purchaseOrderMaster = PurchaseOrderMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/purchase_order_masters/'.$purchaseOrderMaster->id
        );

        $this->assertApiResponse($purchaseOrderMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_purchase_order_master()
    {
        $purchaseOrderMaster = PurchaseOrderMaster::factory()->create();
        $editedPurchaseOrderMaster = PurchaseOrderMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/purchase_order_masters/'.$purchaseOrderMaster->id,
            $editedPurchaseOrderMaster
        );

        $this->assertApiResponse($editedPurchaseOrderMaster);
    }

    /**
     * @test
     */
    public function test_delete_purchase_order_master()
    {
        $purchaseOrderMaster = PurchaseOrderMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/purchase_order_masters/'.$purchaseOrderMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/purchase_order_masters/'.$purchaseOrderMaster->id
        );

        $this->response->assertStatus(404);
    }
}
