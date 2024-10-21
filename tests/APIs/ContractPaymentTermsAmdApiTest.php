<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractPaymentTermsAmd;

class ContractPaymentTermsAmdApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_payment_terms_amd()
    {
        $contractPaymentTermsAmd = ContractPaymentTermsAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_payment_terms_amds', $contractPaymentTermsAmd
        );

        $this->assertApiResponse($contractPaymentTermsAmd);
    }

    /**
     * @test
     */
    public function test_read_contract_payment_terms_amd()
    {
        $contractPaymentTermsAmd = ContractPaymentTermsAmd::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_payment_terms_amds/'.$contractPaymentTermsAmd->id
        );

        $this->assertApiResponse($contractPaymentTermsAmd->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_payment_terms_amd()
    {
        $contractPaymentTermsAmd = ContractPaymentTermsAmd::factory()->create();
        $editedContractPaymentTermsAmd = ContractPaymentTermsAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_payment_terms_amds/'.$contractPaymentTermsAmd->id,
            $editedContractPaymentTermsAmd
        );

        $this->assertApiResponse($editedContractPaymentTermsAmd);
    }

    /**
     * @test
     */
    public function test_delete_contract_payment_terms_amd()
    {
        $contractPaymentTermsAmd = ContractPaymentTermsAmd::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_payment_terms_amds/'.$contractPaymentTermsAmd->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_payment_terms_amds/'.$contractPaymentTermsAmd->id
        );

        $this->response->assertStatus(404);
    }
}
