<?php namespace Tests\Repositories;

use App\Models\CompanyDocumentAttachment;
use App\Repositories\CompanyDocumentAttachmentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CompanyDocumentAttachmentRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CompanyDocumentAttachmentRepository
     */
    protected $companyDocumentAttachmentRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->companyDocumentAttachmentRepo = \App::make(CompanyDocumentAttachmentRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_company_document_attachment()
    {
        $companyDocumentAttachment = CompanyDocumentAttachment::factory()->make()->toArray();

        $createdCompanyDocumentAttachment = $this->companyDocumentAttachmentRepo->create($companyDocumentAttachment);

        $createdCompanyDocumentAttachment = $createdCompanyDocumentAttachment->toArray();
        $this->assertArrayHasKey('id', $createdCompanyDocumentAttachment);
        $this->assertNotNull($createdCompanyDocumentAttachment['id'], 'Created CompanyDocumentAttachment must have id specified');
        $this->assertNotNull(CompanyDocumentAttachment::find($createdCompanyDocumentAttachment['id']), 'CompanyDocumentAttachment with given id must be in DB');
        $this->assertModelData($companyDocumentAttachment, $createdCompanyDocumentAttachment);
    }

    /**
     * @test read
     */
    public function test_read_company_document_attachment()
    {
        $companyDocumentAttachment = CompanyDocumentAttachment::factory()->create();

        $dbCompanyDocumentAttachment = $this->companyDocumentAttachmentRepo->find($companyDocumentAttachment->id);

        $dbCompanyDocumentAttachment = $dbCompanyDocumentAttachment->toArray();
        $this->assertModelData($companyDocumentAttachment->toArray(), $dbCompanyDocumentAttachment);
    }

    /**
     * @test update
     */
    public function test_update_company_document_attachment()
    {
        $companyDocumentAttachment = CompanyDocumentAttachment::factory()->create();
        $fakeCompanyDocumentAttachment = CompanyDocumentAttachment::factory()->make()->toArray();

        $updatedCompanyDocumentAttachment = $this->companyDocumentAttachmentRepo->update($fakeCompanyDocumentAttachment, $companyDocumentAttachment->id);

        $this->assertModelData($fakeCompanyDocumentAttachment, $updatedCompanyDocumentAttachment->toArray());
        $dbCompanyDocumentAttachment = $this->companyDocumentAttachmentRepo->find($companyDocumentAttachment->id);
        $this->assertModelData($fakeCompanyDocumentAttachment, $dbCompanyDocumentAttachment->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_company_document_attachment()
    {
        $companyDocumentAttachment = CompanyDocumentAttachment::factory()->create();

        $resp = $this->companyDocumentAttachmentRepo->delete($companyDocumentAttachment->id);

        $this->assertTrue($resp);
        $this->assertNull(CompanyDocumentAttachment::find($companyDocumentAttachment->id), 'CompanyDocumentAttachment should not exist in DB');
    }
}
