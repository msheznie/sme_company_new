<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractSectionDetail;

class ContractSectionDetailApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_section_detail()
    {
        $contractSectionDetail = ContractSectionDetail::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_section_details', $contractSectionDetail
        );

        $this->assertApiResponse($contractSectionDetail);
    }

    /**
     * @test
     */
    public function test_read_contract_section_detail()
    {
        $contractSectionDetail = ContractSectionDetail::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_section_details/'.$contractSectionDetail->id
        );

        $this->assertApiResponse($contractSectionDetail->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_section_detail()
    {
        $contractSectionDetail = ContractSectionDetail::factory()->create();
        $editedContractSectionDetail = ContractSectionDetail::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_section_details/'.$contractSectionDetail->id,
            $editedContractSectionDetail
        );

        $this->assertApiResponse($editedContractSectionDetail);
    }

    /**
     * @test
     */
    public function test_delete_contract_section_detail()
    {
        $contractSectionDetail = ContractSectionDetail::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_section_details/'.$contractSectionDetail->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_section_details/'.$contractSectionDetail->id
        );

        $this->response->assertStatus(404);
    }
}
