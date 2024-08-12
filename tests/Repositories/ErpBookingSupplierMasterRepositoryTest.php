<?php namespace Tests\Repositories;

use App\Models\ErpBookingSupplierMaster;
use App\Repositories\ErpBookingSupplierMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ErpBookingSupplierMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ErpBookingSupplierMasterRepository
     */
    protected $erpBookingSupplierMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->erpBookingSupplierMasterRepo = \App::make(ErpBookingSupplierMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_erp_booking_supplier_master()
    {
        $erpBookingSupplierMaster = ErpBookingSupplierMaster::factory()->make()->toArray();

        $createdErpBookingSupplierMaster = $this->erpBookingSupplierMasterRepo->create($erpBookingSupplierMaster);

        $createdErpBookingSupplierMaster = $createdErpBookingSupplierMaster->toArray();
        $this->assertArrayHasKey('id', $createdErpBookingSupplierMaster);
        $this->assertNotNull($createdErpBookingSupplierMaster['id'], 'Created ErpBookingSupplierMaster must have id specified');
        $this->assertNotNull(ErpBookingSupplierMaster::find($createdErpBookingSupplierMaster['id']), 'ErpBookingSupplierMaster with given id must be in DB');
        $this->assertModelData($erpBookingSupplierMaster, $createdErpBookingSupplierMaster);
    }

    /**
     * @test read
     */
    public function test_read_erp_booking_supplier_master()
    {
        $erpBookingSupplierMaster = ErpBookingSupplierMaster::factory()->create();

        $dbErpBookingSupplierMaster = $this->erpBookingSupplierMasterRepo->find($erpBookingSupplierMaster->id);

        $dbErpBookingSupplierMaster = $dbErpBookingSupplierMaster->toArray();
        $this->assertModelData($erpBookingSupplierMaster->toArray(), $dbErpBookingSupplierMaster);
    }

    /**
     * @test update
     */
    public function test_update_erp_booking_supplier_master()
    {
        $erpBookingSupplierMaster = ErpBookingSupplierMaster::factory()->create();
        $fakeErpBookingSupplierMaster = ErpBookingSupplierMaster::factory()->make()->toArray();

        $updatedErpBookingSupplierMaster = $this->erpBookingSupplierMasterRepo->update($fakeErpBookingSupplierMaster, $erpBookingSupplierMaster->id);

        $this->assertModelData($fakeErpBookingSupplierMaster, $updatedErpBookingSupplierMaster->toArray());
        $dbErpBookingSupplierMaster = $this->erpBookingSupplierMasterRepo->find($erpBookingSupplierMaster->id);
        $this->assertModelData($fakeErpBookingSupplierMaster, $dbErpBookingSupplierMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_erp_booking_supplier_master()
    {
        $erpBookingSupplierMaster = ErpBookingSupplierMaster::factory()->create();

        $resp = $this->erpBookingSupplierMasterRepo->delete($erpBookingSupplierMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(ErpBookingSupplierMaster::find($erpBookingSupplierMaster->id), 'ErpBookingSupplierMaster should not exist in DB');
    }
}
