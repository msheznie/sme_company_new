<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractUserAssign;

class ContractUserAssignApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_user_assign()
    {
        $contractUserAssign = ContractUserAssign::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_user_assigns', $contractUserAssign
        );

        $this->assertApiResponse($contractUserAssign);
    }

    /**
     * @test
     */
    public function test_read_contract_user_assign()
    {
        $contractUserAssign = ContractUserAssign::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_user_assigns/'.$contractUserAssign->id
        );

        $this->assertApiResponse($contractUserAssign->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_user_assign()
    {
        $contractUserAssign = ContractUserAssign::factory()->create();
        $editedContractUserAssign = ContractUserAssign::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_user_assigns/'.$contractUserAssign->id,
            $editedContractUserAssign
        );

        $this->assertApiResponse($editedContractUserAssign);
    }

    /**
     * @test
     */
    public function test_delete_contract_user_assign()
    {
        $contractUserAssign = ContractUserAssign::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_user_assigns/'.$contractUserAssign->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_user_assigns/'.$contractUserAssign->id
        );

        $this->response->assertStatus(404);
    }
}
