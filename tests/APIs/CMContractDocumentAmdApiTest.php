<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CMContractDocumentAmd;

class CMContractDocumentAmdApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_c_m_contract_document_amd()
    {
        $cMContractDocumentAmd = CMContractDocumentAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/c_m_contract_document_amds', $cMContractDocumentAmd
        );

        $this->assertApiResponse($cMContractDocumentAmd);
    }

    /**
     * @test
     */
    public function test_read_c_m_contract_document_amd()
    {
        $cMContractDocumentAmd = CMContractDocumentAmd::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_document_amds/'.$cMContractDocumentAmd->id
        );

        $this->assertApiResponse($cMContractDocumentAmd->toArray());
    }

    /**
     * @test
     */
    public function test_update_c_m_contract_document_amd()
    {
        $cMContractDocumentAmd = CMContractDocumentAmd::factory()->create();
        $editedCMContractDocumentAmd = CMContractDocumentAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/c_m_contract_document_amds/'.$cMContractDocumentAmd->id,
            $editedCMContractDocumentAmd
        );

        $this->assertApiResponse($editedCMContractDocumentAmd);
    }

    /**
     * @test
     */
    public function test_delete_c_m_contract_document_amd()
    {
        $cMContractDocumentAmd = CMContractDocumentAmd::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/c_m_contract_document_amds/'.$cMContractDocumentAmd->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_document_amds/'.$cMContractDocumentAmd->id
        );

        $this->response->assertStatus(404);
    }
}
