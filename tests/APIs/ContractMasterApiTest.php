<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractMaster;

class ContractMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_master()
    {
        $contractMaster = ContractMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_masters', $contractMaster
        );

        $this->assertApiResponse($contractMaster);
    }

    /**
     * @test
     */
    public function test_read_contract_master()
    {
        $contractMaster = ContractMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_masters/'.$contractMaster->id
        );

        $this->assertApiResponse($contractMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_master()
    {
        $contractMaster = ContractMaster::factory()->create();
        $editedContractMaster = ContractMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_masters/'.$contractMaster->id,
            $editedContractMaster
        );

        $this->assertApiResponse($editedContractMaster);
    }

    /**
     * @test
     */
    public function test_delete_contract_master()
    {
        $contractMaster = ContractMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_masters/'.$contractMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_masters/'.$contractMaster->id
        );

        $this->response->assertStatus(404);
    }
}
