<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CompanyDocumentAttachment;

class CompanyDocumentAttachmentApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_company_document_attachment()
    {
        $companyDocumentAttachment = CompanyDocumentAttachment::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/company_document_attachments', $companyDocumentAttachment
        );

        $this->assertApiResponse($companyDocumentAttachment);
    }

    /**
     * @test
     */
    public function test_read_company_document_attachment()
    {
        $companyDocumentAttachment = CompanyDocumentAttachment::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/company_document_attachments/'.$companyDocumentAttachment->id
        );

        $this->assertApiResponse($companyDocumentAttachment->toArray());
    }

    /**
     * @test
     */
    public function test_update_company_document_attachment()
    {
        $companyDocumentAttachment = CompanyDocumentAttachment::factory()->create();
        $editedCompanyDocumentAttachment = CompanyDocumentAttachment::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/company_document_attachments/'.$companyDocumentAttachment->id,
            $editedCompanyDocumentAttachment
        );

        $this->assertApiResponse($editedCompanyDocumentAttachment);
    }

    /**
     * @test
     */
    public function test_delete_company_document_attachment()
    {
        $companyDocumentAttachment = CompanyDocumentAttachment::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/company_document_attachments/'.$companyDocumentAttachment->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/company_document_attachments/'.$companyDocumentAttachment->id
        );

        $this->response->assertStatus(404);
    }
}
