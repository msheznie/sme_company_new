<?php namespace Tests\Repositories;

use App\Models\ErpDocumentReferedHistory;
use App\Repositories\ErpDocumentReferedHistoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ErpDocumentReferedHistoryRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ErpDocumentReferedHistoryRepository
     */
    protected $erpDocumentReferedHistoryRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->erpDocumentReferedHistoryRepo = \App::make(ErpDocumentReferedHistoryRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_erp_document_refered_history()
    {
        $erpDocumentReferedHistory = ErpDocumentReferedHistory::factory()->make()->toArray();

        $createdErpDocumentReferedHistory = $this->erpDocumentReferedHistoryRepo->create($erpDocumentReferedHistory);

        $createdErpDocumentReferedHistory = $createdErpDocumentReferedHistory->toArray();
        $this->assertArrayHasKey('id', $createdErpDocumentReferedHistory);
        $this->assertNotNull($createdErpDocumentReferedHistory['id'], 'Created ErpDocumentReferedHistory must have id specified');
        $this->assertNotNull(ErpDocumentReferedHistory::find($createdErpDocumentReferedHistory['id']), 'ErpDocumentReferedHistory with given id must be in DB');
        $this->assertModelData($erpDocumentReferedHistory, $createdErpDocumentReferedHistory);
    }

    /**
     * @test read
     */
    public function test_read_erp_document_refered_history()
    {
        $erpDocumentReferedHistory = ErpDocumentReferedHistory::factory()->create();

        $dbErpDocumentReferedHistory = $this->erpDocumentReferedHistoryRepo->find($erpDocumentReferedHistory->id);

        $dbErpDocumentReferedHistory = $dbErpDocumentReferedHistory->toArray();
        $this->assertModelData($erpDocumentReferedHistory->toArray(), $dbErpDocumentReferedHistory);
    }

    /**
     * @test update
     */
    public function test_update_erp_document_refered_history()
    {
        $erpDocumentReferedHistory = ErpDocumentReferedHistory::factory()->create();
        $fakeErpDocumentReferedHistory = ErpDocumentReferedHistory::factory()->make()->toArray();

        $updatedErpDocumentReferedHistory = $this->erpDocumentReferedHistoryRepo->update($fakeErpDocumentReferedHistory, $erpDocumentReferedHistory->id);

        $this->assertModelData($fakeErpDocumentReferedHistory, $updatedErpDocumentReferedHistory->toArray());
        $dbErpDocumentReferedHistory = $this->erpDocumentReferedHistoryRepo->find($erpDocumentReferedHistory->id);
        $this->assertModelData($fakeErpDocumentReferedHistory, $dbErpDocumentReferedHistory->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_erp_document_refered_history()
    {
        $erpDocumentReferedHistory = ErpDocumentReferedHistory::factory()->create();

        $resp = $this->erpDocumentReferedHistoryRepo->delete($erpDocumentReferedHistory->id);

        $this->assertTrue($resp);
        $this->assertNull(ErpDocumentReferedHistory::find($erpDocumentReferedHistory->id), 'ErpDocumentReferedHistory should not exist in DB');
    }
}
