<?php namespace Tests\Repositories;

use App\Models\ErpDocumentApproved;
use App\Repositories\ErpDocumentApprovedRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ErpDocumentApprovedRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ErpDocumentApprovedRepository
     */
    protected $erpDocumentApprovedRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->erpDocumentApprovedRepo = \App::make(ErpDocumentApprovedRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_erp_document_approved()
    {
        $erpDocumentApproved = ErpDocumentApproved::factory()->make()->toArray();

        $createdErpDocumentApproved = $this->erpDocumentApprovedRepo->create($erpDocumentApproved);

        $createdErpDocumentApproved = $createdErpDocumentApproved->toArray();
        $this->assertArrayHasKey('id', $createdErpDocumentApproved);
        $this->assertNotNull($createdErpDocumentApproved['id'], 'Created ErpDocumentApproved must have id specified');
        $this->assertNotNull(ErpDocumentApproved::find($createdErpDocumentApproved['id']), 'ErpDocumentApproved with given id must be in DB');
        $this->assertModelData($erpDocumentApproved, $createdErpDocumentApproved);
    }

    /**
     * @test read
     */
    public function test_read_erp_document_approved()
    {
        $erpDocumentApproved = ErpDocumentApproved::factory()->create();

        $dbErpDocumentApproved = $this->erpDocumentApprovedRepo->find($erpDocumentApproved->id);

        $dbErpDocumentApproved = $dbErpDocumentApproved->toArray();
        $this->assertModelData($erpDocumentApproved->toArray(), $dbErpDocumentApproved);
    }

    /**
     * @test update
     */
    public function test_update_erp_document_approved()
    {
        $erpDocumentApproved = ErpDocumentApproved::factory()->create();
        $fakeErpDocumentApproved = ErpDocumentApproved::factory()->make()->toArray();

        $updatedErpDocumentApproved = $this->erpDocumentApprovedRepo->update($fakeErpDocumentApproved, $erpDocumentApproved->id);

        $this->assertModelData($fakeErpDocumentApproved, $updatedErpDocumentApproved->toArray());
        $dbErpDocumentApproved = $this->erpDocumentApprovedRepo->find($erpDocumentApproved->id);
        $this->assertModelData($fakeErpDocumentApproved, $dbErpDocumentApproved->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_erp_document_approved()
    {
        $erpDocumentApproved = ErpDocumentApproved::factory()->create();

        $resp = $this->erpDocumentApprovedRepo->delete($erpDocumentApproved->id);

        $this->assertTrue($resp);
        $this->assertNull(ErpDocumentApproved::find($erpDocumentApproved->id), 'ErpDocumentApproved should not exist in DB');
    }
}
