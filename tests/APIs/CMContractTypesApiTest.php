<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CMContractTypes;

class CMContractTypesApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_c_m_contract_types()
    {
        $cMContractTypes = CMContractTypes::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/c_m_contract_types', $cMContractTypes
        );

        $this->assertApiResponse($cMContractTypes);
    }

    /**
     * @test
     */
    public function test_read_c_m_contract_types()
    {
        $cMContractTypes = CMContractTypes::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_types/'.$cMContractTypes->id
        );

        $this->assertApiResponse($cMContractTypes->toArray());
    }

    /**
     * @test
     */
    public function test_update_c_m_contract_types()
    {
        $cMContractTypes = CMContractTypes::factory()->create();
        $editedCMContractTypes = CMContractTypes::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/c_m_contract_types/'.$cMContractTypes->id,
            $editedCMContractTypes
        );

        $this->assertApiResponse($editedCMContractTypes);
    }

    /**
     * @test
     */
    public function test_delete_c_m_contract_types()
    {
        $cMContractTypes = CMContractTypes::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/c_m_contract_types/'.$cMContractTypes->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_types/'.$cMContractTypes->id
        );

        $this->response->assertStatus(404);
    }
}
