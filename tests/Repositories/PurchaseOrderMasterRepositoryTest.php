<?php namespace Tests\Repositories;

use App\Models\PurchaseOrderMaster;
use App\Repositories\PurchaseOrderMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PurchaseOrderMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var PurchaseOrderMasterRepository
     */
    protected $purchaseOrderMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->purchaseOrderMasterRepo = \App::make(PurchaseOrderMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_purchase_order_master()
    {
        $purchaseOrderMaster = PurchaseOrderMaster::factory()->make()->toArray();

        $createdPurchaseOrderMaster = $this->purchaseOrderMasterRepo->create($purchaseOrderMaster);

        $createdPurchaseOrderMaster = $createdPurchaseOrderMaster->toArray();
        $this->assertArrayHasKey('id', $createdPurchaseOrderMaster);
        $this->assertNotNull($createdPurchaseOrderMaster['id'], 'Created PurchaseOrderMaster must have id specified');
        $this->assertNotNull(PurchaseOrderMaster::find($createdPurchaseOrderMaster['id']), 'PurchaseOrderMaster with given id must be in DB');
        $this->assertModelData($purchaseOrderMaster, $createdPurchaseOrderMaster);
    }

    /**
     * @test read
     */
    public function test_read_purchase_order_master()
    {
        $purchaseOrderMaster = PurchaseOrderMaster::factory()->create();

        $dbPurchaseOrderMaster = $this->purchaseOrderMasterRepo->find($purchaseOrderMaster->id);

        $dbPurchaseOrderMaster = $dbPurchaseOrderMaster->toArray();
        $this->assertModelData($purchaseOrderMaster->toArray(), $dbPurchaseOrderMaster);
    }

    /**
     * @test update
     */
    public function test_update_purchase_order_master()
    {
        $purchaseOrderMaster = PurchaseOrderMaster::factory()->create();
        $fakePurchaseOrderMaster = PurchaseOrderMaster::factory()->make()->toArray();

        $updatedPurchaseOrderMaster = $this->purchaseOrderMasterRepo->update($fakePurchaseOrderMaster, $purchaseOrderMaster->id);

        $this->assertModelData($fakePurchaseOrderMaster, $updatedPurchaseOrderMaster->toArray());
        $dbPurchaseOrderMaster = $this->purchaseOrderMasterRepo->find($purchaseOrderMaster->id);
        $this->assertModelData($fakePurchaseOrderMaster, $dbPurchaseOrderMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_purchase_order_master()
    {
        $purchaseOrderMaster = PurchaseOrderMaster::factory()->create();

        $resp = $this->purchaseOrderMasterRepo->delete($purchaseOrderMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(PurchaseOrderMaster::find($purchaseOrderMaster->id), 'PurchaseOrderMaster should not exist in DB');
    }
}
