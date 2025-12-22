<?php namespace Tests\Repositories;

use App\Models\ErpDocumentAttachmentsAmd;
use App\Repositories\ErpDocumentAttachmentsAmdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ErpDocumentAttachmentsAmdRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ErpDocumentAttachmentsAmdRepository
     */
    protected $erpDocumentAttachmentsAmdRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->erpDocumentAttachmentsAmdRepo = \App::make(ErpDocumentAttachmentsAmdRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_erp_document_attachments_amd()
    {
        $erpDocumentAttachmentsAmd = ErpDocumentAttachmentsAmd::factory()->make()->toArray();

        $createdErpDocumentAttachmentsAmd = $this->erpDocumentAttachmentsAmdRepo->create($erpDocumentAttachmentsAmd);

        $createdErpDocumentAttachmentsAmd = $createdErpDocumentAttachmentsAmd->toArray();
        $this->assertArrayHasKey('id', $createdErpDocumentAttachmentsAmd);
        $this->assertNotNull($createdErpDocumentAttachmentsAmd['id'], 'Created ErpDocumentAttachmentsAmd must have id specified');
        $this->assertNotNull(ErpDocumentAttachmentsAmd::find($createdErpDocumentAttachmentsAmd['id']), 'ErpDocumentAttachmentsAmd with given id must be in DB');
        $this->assertModelData($erpDocumentAttachmentsAmd, $createdErpDocumentAttachmentsAmd);
    }

    /**
     * @test read
     */
    public function test_read_erp_document_attachments_amd()
    {
        $erpDocumentAttachmentsAmd = ErpDocumentAttachmentsAmd::factory()->create();

        $dbErpDocumentAttachmentsAmd = $this->erpDocumentAttachmentsAmdRepo->find($erpDocumentAttachmentsAmd->id);

        $dbErpDocumentAttachmentsAmd = $dbErpDocumentAttachmentsAmd->toArray();
        $this->assertModelData($erpDocumentAttachmentsAmd->toArray(), $dbErpDocumentAttachmentsAmd);
    }

    /**
     * @test update
     */
    public function test_update_erp_document_attachments_amd()
    {
        $erpDocumentAttachmentsAmd = ErpDocumentAttachmentsAmd::factory()->create();
        $fakeErpDocumentAttachmentsAmd = ErpDocumentAttachmentsAmd::factory()->make()->toArray();

        $updatedErpDocumentAttachmentsAmd = $this->erpDocumentAttachmentsAmdRepo->update($fakeErpDocumentAttachmentsAmd, $erpDocumentAttachmentsAmd->id);

        $this->assertModelData($fakeErpDocumentAttachmentsAmd, $updatedErpDocumentAttachmentsAmd->toArray());
        $dbErpDocumentAttachmentsAmd = $this->erpDocumentAttachmentsAmdRepo->find($erpDocumentAttachmentsAmd->id);
        $this->assertModelData($fakeErpDocumentAttachmentsAmd, $dbErpDocumentAttachmentsAmd->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_erp_document_attachments_amd()
    {
        $erpDocumentAttachmentsAmd = ErpDocumentAttachmentsAmd::factory()->create();

        $resp = $this->erpDocumentAttachmentsAmdRepo->delete($erpDocumentAttachmentsAmd->id);

        $this->assertTrue($resp);
        $this->assertNull(ErpDocumentAttachmentsAmd::find($erpDocumentAttachmentsAmd->id), 'ErpDocumentAttachmentsAmd should not exist in DB');
    }
}
