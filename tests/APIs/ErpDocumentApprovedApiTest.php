<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ErpDocumentApproved;

class ErpDocumentApprovedApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_erp_document_approved()
    {
        $erpDocumentApproved = ErpDocumentApproved::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/erp_document_approveds', $erpDocumentApproved
        );

        $this->assertApiResponse($erpDocumentApproved);
    }

    /**
     * @test
     */
    public function test_read_erp_document_approved()
    {
        $erpDocumentApproved = ErpDocumentApproved::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/erp_document_approveds/'.$erpDocumentApproved->id
        );

        $this->assertApiResponse($erpDocumentApproved->toArray());
    }

    /**
     * @test
     */
    public function test_update_erp_document_approved()
    {
        $erpDocumentApproved = ErpDocumentApproved::factory()->create();
        $editedErpDocumentApproved = ErpDocumentApproved::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/erp_document_approveds/'.$erpDocumentApproved->id,
            $editedErpDocumentApproved
        );

        $this->assertApiResponse($editedErpDocumentApproved);
    }

    /**
     * @test
     */
    public function test_delete_erp_document_approved()
    {
        $erpDocumentApproved = ErpDocumentApproved::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/erp_document_approveds/'.$erpDocumentApproved->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/erp_document_approveds/'.$erpDocumentApproved->id
        );

        $this->response->assertStatus(404);
    }
}
