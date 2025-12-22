<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractBoqItems;

class ContractBoqItemsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_boq_items()
    {
        $contractBoqItems = ContractBoqItems::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_boq_items', $contractBoqItems
        );

        $this->assertApiResponse($contractBoqItems);
    }

    /**
     * @test
     */
    public function test_read_contract_boq_items()
    {
        $contractBoqItems = ContractBoqItems::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_boq_items/'.$contractBoqItems->id
        );

        $this->assertApiResponse($contractBoqItems->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_boq_items()
    {
        $contractBoqItems = ContractBoqItems::factory()->create();
        $editedContractBoqItems = ContractBoqItems::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_boq_items/'.$contractBoqItems->id,
            $editedContractBoqItems
        );

        $this->assertApiResponse($editedContractBoqItems);
    }

    /**
     * @test
     */
    public function test_delete_contract_boq_items()
    {
        $contractBoqItems = ContractBoqItems::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_boq_items/'.$contractBoqItems->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_boq_items/'.$contractBoqItems->id
        );

        $this->response->assertStatus(404);
    }
}
