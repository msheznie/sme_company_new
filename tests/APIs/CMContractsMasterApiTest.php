<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CMContractsMaster;

class CMContractsMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_c_m_contracts_master()
    {
        $cMContractsMaster = CMContractsMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/c_m_contracts_masters', $cMContractsMaster
        );

        $this->assertApiResponse($cMContractsMaster);
    }

    /**
     * @test
     */
    public function test_read_c_m_contracts_master()
    {
        $cMContractsMaster = CMContractsMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/c_m_contracts_masters/'.$cMContractsMaster->id
        );

        $this->assertApiResponse($cMContractsMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_c_m_contracts_master()
    {
        $cMContractsMaster = CMContractsMaster::factory()->create();
        $editedCMContractsMaster = CMContractsMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/c_m_contracts_masters/'.$cMContractsMaster->id,
            $editedCMContractsMaster
        );

        $this->assertApiResponse($editedCMContractsMaster);
    }

    /**
     * @test
     */
    public function test_delete_c_m_contracts_master()
    {
        $cMContractsMaster = CMContractsMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/c_m_contracts_masters/'.$cMContractsMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/c_m_contracts_masters/'.$cMContractsMaster->id
        );

        $this->response->assertStatus(404);
    }
}
