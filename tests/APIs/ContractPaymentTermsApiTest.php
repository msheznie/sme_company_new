<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractPaymentTerms;

class ContractPaymentTermsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_payment_terms()
    {
        $contractPaymentTerms = ContractPaymentTerms::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_payment_terms', $contractPaymentTerms
        );

        $this->assertApiResponse($contractPaymentTerms);
    }

    /**
     * @test
     */
    public function test_read_contract_payment_terms()
    {
        $contractPaymentTerms = ContractPaymentTerms::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_payment_terms/'.$contractPaymentTerms->id
        );

        $this->assertApiResponse($contractPaymentTerms->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_payment_terms()
    {
        $contractPaymentTerms = ContractPaymentTerms::factory()->create();
        $editedContractPaymentTerms = ContractPaymentTerms::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_payment_terms/'.$contractPaymentTerms->id,
            $editedContractPaymentTerms
        );

        $this->assertApiResponse($editedContractPaymentTerms);
    }

    /**
     * @test
     */
    public function test_delete_contract_payment_terms()
    {
        $contractPaymentTerms = ContractPaymentTerms::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_payment_terms/'.$contractPaymentTerms->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_payment_terms/'.$contractPaymentTerms->id
        );

        $this->response->assertStatus(404);
    }
}
