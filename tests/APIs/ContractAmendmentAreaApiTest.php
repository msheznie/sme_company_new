<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractAmendmentArea;

class ContractAmendmentAreaApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_amendment_area()
    {
        $contractAmendmentArea = ContractAmendmentArea::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_amendment_areas', $contractAmendmentArea
        );

        $this->assertApiResponse($contractAmendmentArea);
    }

    /**
     * @test
     */
    public function test_read_contract_amendment_area()
    {
        $contractAmendmentArea = ContractAmendmentArea::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_amendment_areas/'.$contractAmendmentArea->id
        );

        $this->assertApiResponse($contractAmendmentArea->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_amendment_area()
    {
        $contractAmendmentArea = ContractAmendmentArea::factory()->create();
        $editedContractAmendmentArea = ContractAmendmentArea::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_amendment_areas/'.$contractAmendmentArea->id,
            $editedContractAmendmentArea
        );

        $this->assertApiResponse($editedContractAmendmentArea);
    }

    /**
     * @test
     */
    public function test_delete_contract_amendment_area()
    {
        $contractAmendmentArea = ContractAmendmentArea::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_amendment_areas/'.$contractAmendmentArea->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_amendment_areas/'.$contractAmendmentArea->id
        );

        $this->response->assertStatus(404);
    }
}
