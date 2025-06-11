<?php namespace Tests\Repositories;

use App\Models\SupplierAssigned;
use App\Repositories\SupplierAssignedRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class SupplierAssignedRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var SupplierAssignedRepository
     */
    protected $supplierAssignedRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->supplierAssignedRepo = \App::make(SupplierAssignedRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_supplier_assigned()
    {
        $supplierAssigned = SupplierAssigned::factory()->make()->toArray();

        $createdSupplierAssigned = $this->supplierAssignedRepo->create($supplierAssigned);

        $createdSupplierAssigned = $createdSupplierAssigned->toArray();
        $this->assertArrayHasKey('id', $createdSupplierAssigned);
        $this->assertNotNull($createdSupplierAssigned['id'], 'Created SupplierAssigned must have id specified');
        $this->assertNotNull(SupplierAssigned::find($createdSupplierAssigned['id']), 'SupplierAssigned with given id must be in DB');
        $this->assertModelData($supplierAssigned, $createdSupplierAssigned);
    }

    /**
     * @test read
     */
    public function test_read_supplier_assigned()
    {
        $supplierAssigned = SupplierAssigned::factory()->create();

        $dbSupplierAssigned = $this->supplierAssignedRepo->find($supplierAssigned->id);

        $dbSupplierAssigned = $dbSupplierAssigned->toArray();
        $this->assertModelData($supplierAssigned->toArray(), $dbSupplierAssigned);
    }

    /**
     * @test update
     */
    public function test_update_supplier_assigned()
    {
        $supplierAssigned = SupplierAssigned::factory()->create();
        $fakeSupplierAssigned = SupplierAssigned::factory()->make()->toArray();

        $updatedSupplierAssigned = $this->supplierAssignedRepo->update($fakeSupplierAssigned, $supplierAssigned->id);

        $this->assertModelData($fakeSupplierAssigned, $updatedSupplierAssigned->toArray());
        $dbSupplierAssigned = $this->supplierAssignedRepo->find($supplierAssigned->id);
        $this->assertModelData($fakeSupplierAssigned, $dbSupplierAssigned->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_supplier_assigned()
    {
        $supplierAssigned = SupplierAssigned::factory()->create();

        $resp = $this->supplierAssignedRepo->delete($supplierAssigned->id);

        $this->assertTrue($resp);
        $this->assertNull(SupplierAssigned::find($supplierAssigned->id), 'SupplierAssigned should not exist in DB');
    }
}
