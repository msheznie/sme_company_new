<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractAdditionalDocumentAmd;

class ContractAdditionalDocumentAmdApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_additional_document_amd()
    {
        $contractAdditionalDocumentAmd = ContractAdditionalDocumentAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_additional_document_amds', $contractAdditionalDocumentAmd
        );

        $this->assertApiResponse($contractAdditionalDocumentAmd);
    }

    /**
     * @test
     */
    public function test_read_contract_additional_document_amd()
    {
        $contractAdditionalDocumentAmd = ContractAdditionalDocumentAmd::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_additional_document_amds/'.$contractAdditionalDocumentAmd->id
        );

        $this->assertApiResponse($contractAdditionalDocumentAmd->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_additional_document_amd()
    {
        $contractAdditionalDocumentAmd = ContractAdditionalDocumentAmd::factory()->create();
        $editedContractAdditionalDocumentAmd = ContractAdditionalDocumentAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_additional_document_amds/'.$contractAdditionalDocumentAmd->id,
            $editedContractAdditionalDocumentAmd
        );

        $this->assertApiResponse($editedContractAdditionalDocumentAmd);
    }

    /**
     * @test
     */
    public function test_delete_contract_additional_document_amd()
    {
        $contractAdditionalDocumentAmd = ContractAdditionalDocumentAmd::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_additional_document_amds/'.$contractAdditionalDocumentAmd->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_additional_document_amds/'.$contractAdditionalDocumentAmd->id
        );

        $this->response->assertStatus(404);
    }
}
