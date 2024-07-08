<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CMContractBoqItemsAmd;

class CMContractBoqItemsAmdApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_c_m_contract_boq_items_amd()
    {
        $cMContractBoqItemsAmd = CMContractBoqItemsAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/c_m_contract_boq_items_amds', $cMContractBoqItemsAmd
        );

        $this->assertApiResponse($cMContractBoqItemsAmd);
    }

    /**
     * @test
     */
    public function test_read_c_m_contract_boq_items_amd()
    {
        $cMContractBoqItemsAmd = CMContractBoqItemsAmd::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_boq_items_amds/'.$cMContractBoqItemsAmd->id
        );

        $this->assertApiResponse($cMContractBoqItemsAmd->toArray());
    }

    /**
     * @test
     */
    public function test_update_c_m_contract_boq_items_amd()
    {
        $cMContractBoqItemsAmd = CMContractBoqItemsAmd::factory()->create();
        $editedCMContractBoqItemsAmd = CMContractBoqItemsAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/c_m_contract_boq_items_amds/'.$cMContractBoqItemsAmd->id,
            $editedCMContractBoqItemsAmd
        );

        $this->assertApiResponse($editedCMContractBoqItemsAmd);
    }

    /**
     * @test
     */
    public function test_delete_c_m_contract_boq_items_amd()
    {
        $cMContractBoqItemsAmd = CMContractBoqItemsAmd::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/c_m_contract_boq_items_amds/'.$cMContractBoqItemsAmd->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_boq_items_amds/'.$cMContractBoqItemsAmd->id
        );

        $this->response->assertStatus(404);
    }
}
