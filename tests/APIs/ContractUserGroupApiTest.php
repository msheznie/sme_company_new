<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractUserGroup;

class ContractUserGroupApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_user_group()
    {
        $contractUserGroup = ContractUserGroup::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_user_groups', $contractUserGroup
        );

        $this->assertApiResponse($contractUserGroup);
    }

    /**
     * @test
     */
    public function test_read_contract_user_group()
    {
        $contractUserGroup = ContractUserGroup::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_user_groups/'.$contractUserGroup->id
        );

        $this->assertApiResponse($contractUserGroup->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_user_group()
    {
        $contractUserGroup = ContractUserGroup::factory()->create();
        $editedContractUserGroup = ContractUserGroup::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_user_groups/'.$contractUserGroup->id,
            $editedContractUserGroup
        );

        $this->assertApiResponse($editedContractUserGroup);
    }

    /**
     * @test
     */
    public function test_delete_contract_user_group()
    {
        $contractUserGroup = ContractUserGroup::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_user_groups/'.$contractUserGroup->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_user_groups/'.$contractUserGroup->id
        );

        $this->response->assertStatus(404);
    }
}
