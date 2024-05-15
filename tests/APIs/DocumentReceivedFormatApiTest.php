<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\DocumentReceivedFormat;

class DocumentReceivedFormatApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_document_received_format()
    {
        $documentReceivedFormat = DocumentReceivedFormat::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/document_received_formats', $documentReceivedFormat
        );

        $this->assertApiResponse($documentReceivedFormat);
    }

    /**
     * @test
     */
    public function test_read_document_received_format()
    {
        $documentReceivedFormat = DocumentReceivedFormat::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/document_received_formats/'.$documentReceivedFormat->id
        );

        $this->assertApiResponse($documentReceivedFormat->toArray());
    }

    /**
     * @test
     */
    public function test_update_document_received_format()
    {
        $documentReceivedFormat = DocumentReceivedFormat::factory()->create();
        $editedDocumentReceivedFormat = DocumentReceivedFormat::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/document_received_formats/'.$documentReceivedFormat->id,
            $editedDocumentReceivedFormat
        );

        $this->assertApiResponse($editedDocumentReceivedFormat);
    }

    /**
     * @test
     */
    public function test_delete_document_received_format()
    {
        $documentReceivedFormat = DocumentReceivedFormat::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/document_received_formats/'.$documentReceivedFormat->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/document_received_formats/'.$documentReceivedFormat->id
        );

        $this->response->assertStatus(404);
    }
}
