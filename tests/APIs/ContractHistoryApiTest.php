<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractHistory;

class ContractHistoryApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_history()
    {
        $contractHistory = ContractHistory::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_histories', $contractHistory
        );

        $this->assertApiResponse($contractHistory);
    }

    /**
     * @test
     */
    public function test_read_contract_history()
    {
        $contractHistory = ContractHistory::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_histories/'.$contractHistory->id
        );

        $this->assertApiResponse($contractHistory->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_history()
    {
        $contractHistory = ContractHistory::factory()->create();
        $editedContractHistory = ContractHistory::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_histories/'.$contractHistory->id,
            $editedContractHistory
        );

        $this->assertApiResponse($editedContractHistory);
    }

    /**
     * @test
     */
    public function test_delete_contract_history()
    {
        $contractHistory = ContractHistory::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_histories/'.$contractHistory->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_histories/'.$contractHistory->id
        );

        $this->response->assertStatus(404);
    }
}
