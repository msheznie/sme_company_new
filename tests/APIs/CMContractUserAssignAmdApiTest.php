<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CMContractUserAssignAmd;

class CMContractUserAssignAmdApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_c_m_contract_user_assign_amd()
    {
        $cMContractUserAssignAmd = CMContractUserAssignAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/c_m_contract_user_assign_amds', $cMContractUserAssignAmd
        );

        $this->assertApiResponse($cMContractUserAssignAmd);
    }

    /**
     * @test
     */
    public function test_read_c_m_contract_user_assign_amd()
    {
        $cMContractUserAssignAmd = CMContractUserAssignAmd::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_user_assign_amds/'.$cMContractUserAssignAmd->id
        );

        $this->assertApiResponse($cMContractUserAssignAmd->toArray());
    }

    /**
     * @test
     */
    public function test_update_c_m_contract_user_assign_amd()
    {
        $cMContractUserAssignAmd = CMContractUserAssignAmd::factory()->create();
        $editedCMContractUserAssignAmd = CMContractUserAssignAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/c_m_contract_user_assign_amds/'.$cMContractUserAssignAmd->id,
            $editedCMContractUserAssignAmd
        );

        $this->assertApiResponse($editedCMContractUserAssignAmd);
    }

    /**
     * @test
     */
    public function test_delete_c_m_contract_user_assign_amd()
    {
        $cMContractUserAssignAmd = CMContractUserAssignAmd::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/c_m_contract_user_assign_amds/'.$cMContractUserAssignAmd->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_user_assign_amds/'.$cMContractUserAssignAmd->id
        );

        $this->response->assertStatus(404);
    }
}
