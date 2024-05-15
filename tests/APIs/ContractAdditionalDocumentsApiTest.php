<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractAdditionalDocuments;

class ContractAdditionalDocumentsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_additional_documents()
    {
        $contractAdditionalDocuments = ContractAdditionalDocuments::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_additional_documents', $contractAdditionalDocuments
        );

        $this->assertApiResponse($contractAdditionalDocuments);
    }

    /**
     * @test
     */
    public function test_read_contract_additional_documents()
    {
        $contractAdditionalDocuments = ContractAdditionalDocuments::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_additional_documents/'.$contractAdditionalDocuments->id
        );

        $this->assertApiResponse($contractAdditionalDocuments->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_additional_documents()
    {
        $contractAdditionalDocuments = ContractAdditionalDocuments::factory()->create();
        $editedContractAdditionalDocuments = ContractAdditionalDocuments::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_additional_documents/'.$contractAdditionalDocuments->id,
            $editedContractAdditionalDocuments
        );

        $this->assertApiResponse($editedContractAdditionalDocuments);
    }

    /**
     * @test
     */
    public function test_delete_contract_additional_documents()
    {
        $contractAdditionalDocuments = ContractAdditionalDocuments::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_additional_documents/'.$contractAdditionalDocuments->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_additional_documents/'.$contractAdditionalDocuments->id
        );

        $this->response->assertStatus(404);
    }
}
