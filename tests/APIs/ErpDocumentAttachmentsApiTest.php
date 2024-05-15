<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ErpDocumentAttachments;

class ErpDocumentAttachmentsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_erp_document_attachments()
    {
        $erpDocumentAttachments = ErpDocumentAttachments::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/erp_document_attachments', $erpDocumentAttachments
        );

        $this->assertApiResponse($erpDocumentAttachments);
    }

    /**
     * @test
     */
    public function test_read_erp_document_attachments()
    {
        $erpDocumentAttachments = ErpDocumentAttachments::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/erp_document_attachments/'.$erpDocumentAttachments->id
        );

        $this->assertApiResponse($erpDocumentAttachments->toArray());
    }

    /**
     * @test
     */
    public function test_update_erp_document_attachments()
    {
        $erpDocumentAttachments = ErpDocumentAttachments::factory()->create();
        $editedErpDocumentAttachments = ErpDocumentAttachments::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/erp_document_attachments/'.$erpDocumentAttachments->id,
            $editedErpDocumentAttachments
        );

        $this->assertApiResponse($editedErpDocumentAttachments);
    }

    /**
     * @test
     */
    public function test_delete_erp_document_attachments()
    {
        $erpDocumentAttachments = ErpDocumentAttachments::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/erp_document_attachments/'.$erpDocumentAttachments->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/erp_document_attachments/'.$erpDocumentAttachments->id
        );

        $this->response->assertStatus(404);
    }
}
