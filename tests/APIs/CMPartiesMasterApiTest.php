<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CMPartiesMaster;

class CMPartiesMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_c_m_parties_master()
    {
        $cMPartiesMaster = CMPartiesMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/c_m_parties_masters', $cMPartiesMaster
        );

        $this->assertApiResponse($cMPartiesMaster);
    }

    /**
     * @test
     */
    public function test_read_c_m_parties_master()
    {
        $cMPartiesMaster = CMPartiesMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/c_m_parties_masters/'.$cMPartiesMaster->id
        );

        $this->assertApiResponse($cMPartiesMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_c_m_parties_master()
    {
        $cMPartiesMaster = CMPartiesMaster::factory()->create();
        $editedCMPartiesMaster = CMPartiesMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/c_m_parties_masters/'.$cMPartiesMaster->id,
            $editedCMPartiesMaster
        );

        $this->assertApiResponse($editedCMPartiesMaster);
    }

    /**
     * @test
     */
    public function test_delete_c_m_parties_master()
    {
        $cMPartiesMaster = CMPartiesMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/c_m_parties_masters/'.$cMPartiesMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/c_m_parties_masters/'.$cMPartiesMaster->id
        );

        $this->response->assertStatus(404);
    }
}
