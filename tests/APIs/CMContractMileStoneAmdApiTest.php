<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CMContractMileStoneAmd;

class CMContractMileStoneAmdApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_c_m_contract_mile_stone_amd()
    {
        $cMContractMileStoneAmd = CMContractMileStoneAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/c_m_contract_mile_stone_amds', $cMContractMileStoneAmd
        );

        $this->assertApiResponse($cMContractMileStoneAmd);
    }

    /**
     * @test
     */
    public function test_read_c_m_contract_mile_stone_amd()
    {
        $cMContractMileStoneAmd = CMContractMileStoneAmd::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_mile_stone_amds/'.$cMContractMileStoneAmd->id
        );

        $this->assertApiResponse($cMContractMileStoneAmd->toArray());
    }

    /**
     * @test
     */
    public function test_update_c_m_contract_mile_stone_amd()
    {
        $cMContractMileStoneAmd = CMContractMileStoneAmd::factory()->create();
        $editedCMContractMileStoneAmd = CMContractMileStoneAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/c_m_contract_mile_stone_amds/'.$cMContractMileStoneAmd->id,
            $editedCMContractMileStoneAmd
        );

        $this->assertApiResponse($editedCMContractMileStoneAmd);
    }

    /**
     * @test
     */
    public function test_delete_c_m_contract_mile_stone_amd()
    {
        $cMContractMileStoneAmd = CMContractMileStoneAmd::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/c_m_contract_mile_stone_amds/'.$cMContractMileStoneAmd->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_mile_stone_amds/'.$cMContractMileStoneAmd->id
        );

        $this->response->assertStatus(404);
    }
}
