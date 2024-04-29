<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractDeliverables;

class ContractDeliverablesApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_deliverables()
    {
        $contractDeliverables = ContractDeliverables::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_deliverables', $contractDeliverables
        );

        $this->assertApiResponse($contractDeliverables);
    }

    /**
     * @test
     */
    public function test_read_contract_deliverables()
    {
        $contractDeliverables = ContractDeliverables::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_deliverables/'.$contractDeliverables->id
        );

        $this->assertApiResponse($contractDeliverables->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_deliverables()
    {
        $contractDeliverables = ContractDeliverables::factory()->create();
        $editedContractDeliverables = ContractDeliverables::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_deliverables/'.$contractDeliverables->id,
            $editedContractDeliverables
        );

        $this->assertApiResponse($editedContractDeliverables);
    }

    /**
     * @test
     */
    public function test_delete_contract_deliverables()
    {
        $contractDeliverables = ContractDeliverables::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_deliverables/'.$contractDeliverables->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_deliverables/'.$contractDeliverables->id
        );

        $this->response->assertStatus(404);
    }
}
