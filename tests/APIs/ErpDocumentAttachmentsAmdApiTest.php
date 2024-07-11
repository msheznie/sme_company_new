<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ErpDocumentAttachmentsAmd;

class ErpDocumentAttachmentsAmdApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_erp_document_attachments_amd()
    {
        $erpDocumentAttachmentsAmd = ErpDocumentAttachmentsAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/erp_document_attachments_amds', $erpDocumentAttachmentsAmd
        );

        $this->assertApiResponse($erpDocumentAttachmentsAmd);
    }

    /**
     * @test
     */
    public function test_read_erp_document_attachments_amd()
    {
        $erpDocumentAttachmentsAmd = ErpDocumentAttachmentsAmd::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/erp_document_attachments_amds/'.$erpDocumentAttachmentsAmd->id
        );

        $this->assertApiResponse($erpDocumentAttachmentsAmd->toArray());
    }

    /**
     * @test
     */
    public function test_update_erp_document_attachments_amd()
    {
        $erpDocumentAttachmentsAmd = ErpDocumentAttachmentsAmd::factory()->create();
        $editedErpDocumentAttachmentsAmd = ErpDocumentAttachmentsAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/erp_document_attachments_amds/'.$erpDocumentAttachmentsAmd->id,
            $editedErpDocumentAttachmentsAmd
        );

        $this->assertApiResponse($editedErpDocumentAttachmentsAmd);
    }

    /**
     * @test
     */
    public function test_delete_erp_document_attachments_amd()
    {
        $erpDocumentAttachmentsAmd = ErpDocumentAttachmentsAmd::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/erp_document_attachments_amds/'.$erpDocumentAttachmentsAmd->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/erp_document_attachments_amds/'.$erpDocumentAttachmentsAmd->id
        );

        $this->response->assertStatus(404);
    }
}
