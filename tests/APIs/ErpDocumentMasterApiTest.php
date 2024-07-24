<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ErpDocumentMaster;

class ErpDocumentMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_erp_document_master()
    {
        $erpDocumentMaster = ErpDocumentMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/erp_document_masters', $erpDocumentMaster
        );

        $this->assertApiResponse($erpDocumentMaster);
    }

    /**
     * @test
     */
    public function test_read_erp_document_master()
    {
        $erpDocumentMaster = ErpDocumentMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/erp_document_masters/'.$erpDocumentMaster->id
        );

        $this->assertApiResponse($erpDocumentMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_erp_document_master()
    {
        $erpDocumentMaster = ErpDocumentMaster::factory()->create();
        $editedErpDocumentMaster = ErpDocumentMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/erp_document_masters/'.$erpDocumentMaster->id,
            $editedErpDocumentMaster
        );

        $this->assertApiResponse($editedErpDocumentMaster);
    }

    /**
     * @test
     */
    public function test_delete_erp_document_master()
    {
        $erpDocumentMaster = ErpDocumentMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/erp_document_masters/'.$erpDocumentMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/erp_document_masters/'.$erpDocumentMaster->id
        );

        $this->response->assertStatus(404);
    }
}
