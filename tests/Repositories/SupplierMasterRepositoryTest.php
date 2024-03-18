<?php namespace Tests\Repositories;

use App\Models\SupplierMaster;
use App\Repositories\SupplierMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class SupplierMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var SupplierMasterRepository
     */
    protected $supplierMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->supplierMasterRepo = \App::make(SupplierMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_supplier_master()
    {
        $supplierMaster = SupplierMaster::factory()->make()->toArray();

        $createdSupplierMaster = $this->supplierMasterRepo->create($supplierMaster);

        $createdSupplierMaster = $createdSupplierMaster->toArray();
        $this->assertArrayHasKey('id', $createdSupplierMaster);
        $this->assertNotNull($createdSupplierMaster['id'], 'Created SupplierMaster must have id specified');
        $this->assertNotNull(SupplierMaster::find($createdSupplierMaster['id']), 'SupplierMaster with given id must be in DB');
        $this->assertModelData($supplierMaster, $createdSupplierMaster);
    }

    /**
     * @test read
     */
    public function test_read_supplier_master()
    {
        $supplierMaster = SupplierMaster::factory()->create();

        $dbSupplierMaster = $this->supplierMasterRepo->find($supplierMaster->id);

        $dbSupplierMaster = $dbSupplierMaster->toArray();
        $this->assertModelData($supplierMaster->toArray(), $dbSupplierMaster);
    }

    /**
     * @test update
     */
    public function test_update_supplier_master()
    {
        $supplierMaster = SupplierMaster::factory()->create();
        $fakeSupplierMaster = SupplierMaster::factory()->make()->toArray();

        $updatedSupplierMaster = $this->supplierMasterRepo->update($fakeSupplierMaster, $supplierMaster->id);

        $this->assertModelData($fakeSupplierMaster, $updatedSupplierMaster->toArray());
        $dbSupplierMaster = $this->supplierMasterRepo->find($supplierMaster->id);
        $this->assertModelData($fakeSupplierMaster, $dbSupplierMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_supplier_master()
    {
        $supplierMaster = SupplierMaster::factory()->create();

        $resp = $this->supplierMasterRepo->delete($supplierMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(SupplierMaster::find($supplierMaster->id), 'SupplierMaster should not exist in DB');
    }
}
