<?php namespace Tests\Repositories;

use App\Models\ErpDocumentAttachments;
use App\Repositories\ErpDocumentAttachmentsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ErpDocumentAttachmentsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ErpDocumentAttachmentsRepository
     */
    protected $erpDocumentAttachmentsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->erpDocumentAttachmentsRepo = \App::make(ErpDocumentAttachmentsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_erp_document_attachments()
    {
        $erpDocumentAttachments = ErpDocumentAttachments::factory()->make()->toArray();

        $createdErpDocumentAttachments = $this->erpDocumentAttachmentsRepo->create($erpDocumentAttachments);

        $createdErpDocumentAttachments = $createdErpDocumentAttachments->toArray();
        $this->assertArrayHasKey('id', $createdErpDocumentAttachments);
        $this->assertNotNull($createdErpDocumentAttachments['id'], 'Created ErpDocumentAttachments must have id specified');
        $this->assertNotNull(ErpDocumentAttachments::find($createdErpDocumentAttachments['id']), 'ErpDocumentAttachments with given id must be in DB');
        $this->assertModelData($erpDocumentAttachments, $createdErpDocumentAttachments);
    }

    /**
     * @test read
     */
    public function test_read_erp_document_attachments()
    {
        $erpDocumentAttachments = ErpDocumentAttachments::factory()->create();

        $dbErpDocumentAttachments = $this->erpDocumentAttachmentsRepo->find($erpDocumentAttachments->id);

        $dbErpDocumentAttachments = $dbErpDocumentAttachments->toArray();
        $this->assertModelData($erpDocumentAttachments->toArray(), $dbErpDocumentAttachments);
    }

    /**
     * @test update
     */
    public function test_update_erp_document_attachments()
    {
        $erpDocumentAttachments = ErpDocumentAttachments::factory()->create();
        $fakeErpDocumentAttachments = ErpDocumentAttachments::factory()->make()->toArray();

        $updatedErpDocumentAttachments = $this->erpDocumentAttachmentsRepo->update($fakeErpDocumentAttachments, $erpDocumentAttachments->id);

        $this->assertModelData($fakeErpDocumentAttachments, $updatedErpDocumentAttachments->toArray());
        $dbErpDocumentAttachments = $this->erpDocumentAttachmentsRepo->find($erpDocumentAttachments->id);
        $this->assertModelData($fakeErpDocumentAttachments, $dbErpDocumentAttachments->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_erp_document_attachments()
    {
        $erpDocumentAttachments = ErpDocumentAttachments::factory()->create();

        $resp = $this->erpDocumentAttachmentsRepo->delete($erpDocumentAttachments->id);

        $this->assertTrue($resp);
        $this->assertNull(ErpDocumentAttachments::find($erpDocumentAttachments->id), 'ErpDocumentAttachments should not exist in DB');
    }
}
