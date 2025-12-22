<?php namespace Tests\Repositories;

use App\Models\PurchaseOrderDetail;
use App\Repositories\PurchaseOrderDetailRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PurchaseOrderDetailRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var PurchaseOrderDetailRepository
     */
    protected $purchaseOrderDetailRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->purchaseOrderDetailRepo = \App::make(PurchaseOrderDetailRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_purchase_order_detail()
    {
        $purchaseOrderDetail = PurchaseOrderDetail::factory()->make()->toArray();

        $createdPurchaseOrderDetail = $this->purchaseOrderDetailRepo->create($purchaseOrderDetail);

        $createdPurchaseOrderDetail = $createdPurchaseOrderDetail->toArray();
        $this->assertArrayHasKey('id', $createdPurchaseOrderDetail);
        $this->assertNotNull($createdPurchaseOrderDetail['id'], 'Created PurchaseOrderDetail must have id specified');
        $this->assertNotNull(PurchaseOrderDetail::find($createdPurchaseOrderDetail['id']), 'PurchaseOrderDetail with given id must be in DB');
        $this->assertModelData($purchaseOrderDetail, $createdPurchaseOrderDetail);
    }

    /**
     * @test read
     */
    public function test_read_purchase_order_detail()
    {
        $purchaseOrderDetail = PurchaseOrderDetail::factory()->create();

        $dbPurchaseOrderDetail = $this->purchaseOrderDetailRepo->find($purchaseOrderDetail->id);

        $dbPurchaseOrderDetail = $dbPurchaseOrderDetail->toArray();
        $this->assertModelData($purchaseOrderDetail->toArray(), $dbPurchaseOrderDetail);
    }

    /**
     * @test update
     */
    public function test_update_purchase_order_detail()
    {
        $purchaseOrderDetail = PurchaseOrderDetail::factory()->create();
        $fakePurchaseOrderDetail = PurchaseOrderDetail::factory()->make()->toArray();

        $updatedPurchaseOrderDetail = $this->purchaseOrderDetailRepo->update($fakePurchaseOrderDetail, $purchaseOrderDetail->id);

        $this->assertModelData($fakePurchaseOrderDetail, $updatedPurchaseOrderDetail->toArray());
        $dbPurchaseOrderDetail = $this->purchaseOrderDetailRepo->find($purchaseOrderDetail->id);
        $this->assertModelData($fakePurchaseOrderDetail, $dbPurchaseOrderDetail->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_purchase_order_detail()
    {
        $purchaseOrderDetail = PurchaseOrderDetail::factory()->create();

        $resp = $this->purchaseOrderDetailRepo->delete($purchaseOrderDetail->id);

        $this->assertTrue($resp);
        $this->assertNull(PurchaseOrderDetail::find($purchaseOrderDetail->id), 'PurchaseOrderDetail should not exist in DB');
    }
}
