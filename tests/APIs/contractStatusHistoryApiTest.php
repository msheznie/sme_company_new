<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\contractStatusHistory;

class contractStatusHistoryApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_status_history()
    {
        $contractStatusHistory = contractStatusHistory::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_status_histories', $contractStatusHistory
        );

        $this->assertApiResponse($contractStatusHistory);
    }

    /**
     * @test
     */
    public function test_read_contract_status_history()
    {
        $contractStatusHistory = contractStatusHistory::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_status_histories/'.$contractStatusHistory->id
        );

        $this->assertApiResponse($contractStatusHistory->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_status_history()
    {
        $contractStatusHistory = contractStatusHistory::factory()->create();
        $editedcontractStatusHistory = contractStatusHistory::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_status_histories/'.$contractStatusHistory->id,
            $editedcontractStatusHistory
        );

        $this->assertApiResponse($editedcontractStatusHistory);
    }

    /**
     * @test
     */
    public function test_delete_contract_status_history()
    {
        $contractStatusHistory = contractStatusHistory::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_status_histories/'.$contractStatusHistory->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_status_histories/'.$contractStatusHistory->id
        );

        $this->response->assertStatus(404);
    }
}
