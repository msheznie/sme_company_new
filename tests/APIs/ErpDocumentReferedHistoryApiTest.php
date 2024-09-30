<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ErpDocumentReferedHistory;

class ErpDocumentReferedHistoryApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_erp_document_refered_history()
    {
        $erpDocumentReferedHistory = ErpDocumentReferedHistory::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/erp_document_refered_histories', $erpDocumentReferedHistory
        );

        $this->assertApiResponse($erpDocumentReferedHistory);
    }

    /**
     * @test
     */
    public function test_read_erp_document_refered_history()
    {
        $erpDocumentReferedHistory = ErpDocumentReferedHistory::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/erp_document_refered_histories/'.$erpDocumentReferedHistory->id
        );

        $this->assertApiResponse($erpDocumentReferedHistory->toArray());
    }

    /**
     * @test
     */
    public function test_update_erp_document_refered_history()
    {
        $erpDocumentReferedHistory = ErpDocumentReferedHistory::factory()->create();
        $editedErpDocumentReferedHistory = ErpDocumentReferedHistory::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/erp_document_refered_histories/'.$erpDocumentReferedHistory->id,
            $editedErpDocumentReferedHistory
        );

        $this->assertApiResponse($editedErpDocumentReferedHistory);
    }

    /**
     * @test
     */
    public function test_delete_erp_document_refered_history()
    {
        $erpDocumentReferedHistory = ErpDocumentReferedHistory::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/erp_document_refered_histories/'.$erpDocumentReferedHistory->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/erp_document_refered_histories/'.$erpDocumentReferedHistory->id
        );

        $this->response->assertStatus(404);
    }
}
