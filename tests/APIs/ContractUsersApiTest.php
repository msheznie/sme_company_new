<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractUsers;

class ContractUsersApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_users()
    {
        $contractUsers = ContractUsers::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_users', $contractUsers
        );

        $this->assertApiResponse($contractUsers);
    }

    /**
     * @test
     */
    public function test_read_contract_users()
    {
        $contractUsers = ContractUsers::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_users/'.$contractUsers->id
        );

        $this->assertApiResponse($contractUsers->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_users()
    {
        $contractUsers = ContractUsers::factory()->create();
        $editedContractUsers = ContractUsers::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_users/'.$contractUsers->id,
            $editedContractUsers
        );

        $this->assertApiResponse($editedContractUsers);
    }

    /**
     * @test
     */
    public function test_delete_contract_users()
    {
        $contractUsers = ContractUsers::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_users/'.$contractUsers->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_users/'.$contractUsers->id
        );

        $this->response->assertStatus(404);
    }
}
