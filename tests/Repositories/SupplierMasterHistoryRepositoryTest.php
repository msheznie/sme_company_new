<?php namespace Tests\Repositories;

use App\Models\SupplierMasterHistory;
use App\Repositories\SupplierMasterHistoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class SupplierMasterHistoryRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var SupplierMasterHistoryRepository
     */
    protected $supplierMasterHistoryRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->supplierMasterHistoryRepo = \App::make(SupplierMasterHistoryRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_supplier_master_history()
    {
        $supplierMasterHistory = SupplierMasterHistory::factory()->make()->toArray();

        $createdSupplierMasterHistory = $this->supplierMasterHistoryRepo->create($supplierMasterHistory);

        $createdSupplierMasterHistory = $createdSupplierMasterHistory->toArray();
        $this->assertArrayHasKey('id', $createdSupplierMasterHistory);
        $this->assertNotNull($createdSupplierMasterHistory['id'], 'Created SupplierMasterHistory must have id specified');
        $this->assertNotNull(SupplierMasterHistory::find($createdSupplierMasterHistory['id']), 'SupplierMasterHistory with given id must be in DB');
        $this->assertModelData($supplierMasterHistory, $createdSupplierMasterHistory);
    }

    /**
     * @test read
     */
    public function test_read_supplier_master_history()
    {
        $supplierMasterHistory = SupplierMasterHistory::factory()->create();

        $dbSupplierMasterHistory = $this->supplierMasterHistoryRepo->find($supplierMasterHistory->id);

        $dbSupplierMasterHistory = $dbSupplierMasterHistory->toArray();
        $this->assertModelData($supplierMasterHistory->toArray(), $dbSupplierMasterHistory);
    }

    /**
     * @test update
     */
    public function test_update_supplier_master_history()
    {
        $supplierMasterHistory = SupplierMasterHistory::factory()->create();
        $fakeSupplierMasterHistory = SupplierMasterHistory::factory()->make()->toArray();

        $updatedSupplierMasterHistory = $this->supplierMasterHistoryRepo->update($fakeSupplierMasterHistory, $supplierMasterHistory->id);

        $this->assertModelData($fakeSupplierMasterHistory, $updatedSupplierMasterHistory->toArray());
        $dbSupplierMasterHistory = $this->supplierMasterHistoryRepo->find($supplierMasterHistory->id);
        $this->assertModelData($fakeSupplierMasterHistory, $dbSupplierMasterHistory->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_supplier_master_history()
    {
        $supplierMasterHistory = SupplierMasterHistory::factory()->create();

        $resp = $this->supplierMasterHistoryRepo->delete($supplierMasterHistory->id);

        $this->assertTrue($resp);
        $this->assertNull(SupplierMasterHistory::find($supplierMasterHistory->id), 'SupplierMasterHistory should not exist in DB');
    }
}
