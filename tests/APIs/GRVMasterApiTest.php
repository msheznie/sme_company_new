<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\GRVMaster;

class GRVMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_g_r_v_master()
    {
        $gRVMaster = GRVMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/g_r_v_masters', $gRVMaster
        );

        $this->assertApiResponse($gRVMaster);
    }

    /**
     * @test
     */
    public function test_read_g_r_v_master()
    {
        $gRVMaster = GRVMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/g_r_v_masters/'.$gRVMaster->id
        );

        $this->assertApiResponse($gRVMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_g_r_v_master()
    {
        $gRVMaster = GRVMaster::factory()->create();
        $editedGRVMaster = GRVMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/g_r_v_masters/'.$gRVMaster->id,
            $editedGRVMaster
        );

        $this->assertApiResponse($editedGRVMaster);
    }

    /**
     * @test
     */
    public function test_delete_g_r_v_master()
    {
        $gRVMaster = GRVMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/g_r_v_masters/'.$gRVMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/g_r_v_masters/'.$gRVMaster->id
        );

        $this->response->assertStatus(404);
    }
}
