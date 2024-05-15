<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractDocument;

class ContractDocumentApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_document()
    {
        $contractDocument = ContractDocument::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_documents', $contractDocument
        );

        $this->assertApiResponse($contractDocument);
    }

    /**
     * @test
     */
    public function test_read_contract_document()
    {
        $contractDocument = ContractDocument::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_documents/'.$contractDocument->id
        );

        $this->assertApiResponse($contractDocument->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_document()
    {
        $contractDocument = ContractDocument::factory()->create();
        $editedContractDocument = ContractDocument::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_documents/'.$contractDocument->id,
            $editedContractDocument
        );

        $this->assertApiResponse($editedContractDocument);
    }

    /**
     * @test
     */
    public function test_delete_contract_document()
    {
        $contractDocument = ContractDocument::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_documents/'.$contractDocument->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_documents/'.$contractDocument->id
        );

        $this->response->assertStatus(404);
    }
}
