<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CMContractDeliverableAmd;

class CMContractDeliverableAmdApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_c_m_contract_deliverable_amd()
    {
        $cMContractDeliverableAmd = CMContractDeliverableAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/c_m_contract_deliverable_amds', $cMContractDeliverableAmd
        );

        $this->assertApiResponse($cMContractDeliverableAmd);
    }

    /**
     * @test
     */
    public function test_read_c_m_contract_deliverable_amd()
    {
        $cMContractDeliverableAmd = CMContractDeliverableAmd::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_deliverable_amds/'.$cMContractDeliverableAmd->id
        );

        $this->assertApiResponse($cMContractDeliverableAmd->toArray());
    }

    /**
     * @test
     */
    public function test_update_c_m_contract_deliverable_amd()
    {
        $cMContractDeliverableAmd = CMContractDeliverableAmd::factory()->create();
        $editedCMContractDeliverableAmd = CMContractDeliverableAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/c_m_contract_deliverable_amds/'.$cMContractDeliverableAmd->id,
            $editedCMContractDeliverableAmd
        );

        $this->assertApiResponse($editedCMContractDeliverableAmd);
    }

    /**
     * @test
     */
    public function test_delete_c_m_contract_deliverable_amd()
    {
        $cMContractDeliverableAmd = CMContractDeliverableAmd::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/c_m_contract_deliverable_amds/'.$cMContractDeliverableAmd->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_deliverable_amds/'.$cMContractDeliverableAmd->id
        );

        $this->response->assertStatus(404);
    }
}
