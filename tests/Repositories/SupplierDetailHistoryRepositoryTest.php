<?php namespace Tests\Repositories;

use App\Models\SupplierDetailHistory;
use App\Repositories\SupplierDetailHistoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class SupplierDetailHistoryRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var SupplierDetailHistoryRepository
     */
    protected $supplierDetailHistoryRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->supplierDetailHistoryRepo = \App::make(SupplierDetailHistoryRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_supplier_detail_history()
    {
        $supplierDetailHistory = SupplierDetailHistory::factory()->make()->toArray();

        $createdSupplierDetailHistory = $this->supplierDetailHistoryRepo->create($supplierDetailHistory);

        $createdSupplierDetailHistory = $createdSupplierDetailHistory->toArray();
        $this->assertArrayHasKey('id', $createdSupplierDetailHistory);
        $this->assertNotNull($createdSupplierDetailHistory['id'], 'Created SupplierDetailHistory must have id specified');
        $this->assertNotNull(SupplierDetailHistory::find($createdSupplierDetailHistory['id']), 'SupplierDetailHistory with given id must be in DB');
        $this->assertModelData($supplierDetailHistory, $createdSupplierDetailHistory);
    }

    /**
     * @test read
     */
    public function test_read_supplier_detail_history()
    {
        $supplierDetailHistory = SupplierDetailHistory::factory()->create();

        $dbSupplierDetailHistory = $this->supplierDetailHistoryRepo->find($supplierDetailHistory->id);

        $dbSupplierDetailHistory = $dbSupplierDetailHistory->toArray();
        $this->assertModelData($supplierDetailHistory->toArray(), $dbSupplierDetailHistory);
    }

    /**
     * @test update
     */
    public function test_update_supplier_detail_history()
    {
        $supplierDetailHistory = SupplierDetailHistory::factory()->create();
        $fakeSupplierDetailHistory = SupplierDetailHistory::factory()->make()->toArray();

        $updatedSupplierDetailHistory = $this->supplierDetailHistoryRepo->update($fakeSupplierDetailHistory, $supplierDetailHistory->id);

        $this->assertModelData($fakeSupplierDetailHistory, $updatedSupplierDetailHistory->toArray());
        $dbSupplierDetailHistory = $this->supplierDetailHistoryRepo->find($supplierDetailHistory->id);
        $this->assertModelData($fakeSupplierDetailHistory, $dbSupplierDetailHistory->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_supplier_detail_history()
    {
        $supplierDetailHistory = SupplierDetailHistory::factory()->create();

        $resp = $this->supplierDetailHistoryRepo->delete($supplierDetailHistory->id);

        $this->assertTrue($resp);
        $this->assertNull(SupplierDetailHistory::find($supplierDetailHistory->id), 'SupplierDetailHistory should not exist in DB');
    }
}
