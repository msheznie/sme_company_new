<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\DocumentMaster;

class DocumentMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_document_master()
    {
        $documentMaster = DocumentMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/document_masters', $documentMaster
        );

        $this->assertApiResponse($documentMaster);
    }

    /**
     * @test
     */
    public function test_read_document_master()
    {
        $documentMaster = DocumentMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/document_masters/'.$documentMaster->id
        );

        $this->assertApiResponse($documentMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_document_master()
    {
        $documentMaster = DocumentMaster::factory()->create();
        $editedDocumentMaster = DocumentMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/document_masters/'.$documentMaster->id,
            $editedDocumentMaster
        );

        $this->assertApiResponse($editedDocumentMaster);
    }

    /**
     * @test
     */
    public function test_delete_document_master()
    {
        $documentMaster = DocumentMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/document_masters/'.$documentMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/document_masters/'.$documentMaster->id
        );

        $this->response->assertStatus(404);
    }
}
