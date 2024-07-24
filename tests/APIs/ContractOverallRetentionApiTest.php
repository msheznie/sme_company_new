<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractOverallRetention;

class ContractOverallRetentionApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_overall_retention()
    {
        $contractOverallRetention = ContractOverallRetention::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_overall_retentions', $contractOverallRetention
        );

        $this->assertApiResponse($contractOverallRetention);
    }

    /**
     * @test
     */
    public function test_read_contract_overall_retention()
    {
        $contractOverallRetention = ContractOverallRetention::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_overall_retentions/'.$contractOverallRetention->id
        );

        $this->assertApiResponse($contractOverallRetention->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_overall_retention()
    {
        $contractOverallRetention = ContractOverallRetention::factory()->create();
        $editedContractOverallRetention = ContractOverallRetention::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_overall_retentions/'.$contractOverallRetention->id,
            $editedContractOverallRetention
        );

        $this->assertApiResponse($editedContractOverallRetention);
    }

    /**
     * @test
     */
    public function test_delete_contract_overall_retention()
    {
        $contractOverallRetention = ContractOverallRetention::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_overall_retentions/'.$contractOverallRetention->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_overall_retentions/'.$contractOverallRetention->id
        );

        $this->response->assertStatus(404);
    }
}
