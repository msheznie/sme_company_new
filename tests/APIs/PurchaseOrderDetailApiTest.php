<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\PurchaseOrderDetail;

class PurchaseOrderDetailApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_purchase_order_detail()
    {
        $purchaseOrderDetail = PurchaseOrderDetail::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/purchase_order_details', $purchaseOrderDetail
        );

        $this->assertApiResponse($purchaseOrderDetail);
    }

    /**
     * @test
     */
    public function test_read_purchase_order_detail()
    {
        $purchaseOrderDetail = PurchaseOrderDetail::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/purchase_order_details/'.$purchaseOrderDetail->id
        );

        $this->assertApiResponse($purchaseOrderDetail->toArray());
    }

    /**
     * @test
     */
    public function test_update_purchase_order_detail()
    {
        $purchaseOrderDetail = PurchaseOrderDetail::factory()->create();
        $editedPurchaseOrderDetail = PurchaseOrderDetail::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/purchase_order_details/'.$purchaseOrderDetail->id,
            $editedPurchaseOrderDetail
        );

        $this->assertApiResponse($editedPurchaseOrderDetail);
    }

    /**
     * @test
     */
    public function test_delete_purchase_order_detail()
    {
        $purchaseOrderDetail = PurchaseOrderDetail::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/purchase_order_details/'.$purchaseOrderDetail->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/purchase_order_details/'.$purchaseOrderDetail->id
        );

        $this->response->assertStatus(404);
    }
}
