<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CMCounterPartiesMaster;

class CMCounterPartiesMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_c_m_counter_parties_master()
    {
        $cMCounterPartiesMaster = CMCounterPartiesMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/c_m_counter_parties_masters', $cMCounterPartiesMaster
        );

        $this->assertApiResponse($cMCounterPartiesMaster);
    }

    /**
     * @test
     */
    public function test_read_c_m_counter_parties_master()
    {
        $cMCounterPartiesMaster = CMCounterPartiesMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/c_m_counter_parties_masters/'.$cMCounterPartiesMaster->id
        );

        $this->assertApiResponse($cMCounterPartiesMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_c_m_counter_parties_master()
    {
        $cMCounterPartiesMaster = CMCounterPartiesMaster::factory()->create();
        $editedCMCounterPartiesMaster = CMCounterPartiesMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/c_m_counter_parties_masters/'.$cMCounterPartiesMaster->id,
            $editedCMCounterPartiesMaster
        );

        $this->assertApiResponse($editedCMCounterPartiesMaster);
    }

    /**
     * @test
     */
    public function test_delete_c_m_counter_parties_master()
    {
        $cMCounterPartiesMaster = CMCounterPartiesMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/c_m_counter_parties_masters/'.$cMCounterPartiesMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/c_m_counter_parties_masters/'.$cMCounterPartiesMaster->id
        );

        $this->response->assertStatus(404);
    }
}
