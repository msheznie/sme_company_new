<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\TimeMaterialConsumptionAmd;

class TimeMaterialConsumptionAmdApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_time_material_consumption_amd()
    {
        $timeMaterialConsumptionAmd = TimeMaterialConsumptionAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/time_material_consumption_amds', $timeMaterialConsumptionAmd
        );

        $this->assertApiResponse($timeMaterialConsumptionAmd);
    }

    /**
     * @test
     */
    public function test_read_time_material_consumption_amd()
    {
        $timeMaterialConsumptionAmd = TimeMaterialConsumptionAmd::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/time_material_consumption_amds/'.$timeMaterialConsumptionAmd->id
        );

        $this->assertApiResponse($timeMaterialConsumptionAmd->toArray());
    }

    /**
     * @test
     */
    public function test_update_time_material_consumption_amd()
    {
        $timeMaterialConsumptionAmd = TimeMaterialConsumptionAmd::factory()->create();
        $editedTimeMaterialConsumptionAmd = TimeMaterialConsumptionAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/time_material_consumption_amds/'.$timeMaterialConsumptionAmd->id,
            $editedTimeMaterialConsumptionAmd
        );

        $this->assertApiResponse($editedTimeMaterialConsumptionAmd);
    }

    /**
     * @test
     */
    public function test_delete_time_material_consumption_amd()
    {
        $timeMaterialConsumptionAmd = TimeMaterialConsumptionAmd::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/time_material_consumption_amds/'.$timeMaterialConsumptionAmd->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/time_material_consumption_amds/'.$timeMaterialConsumptionAmd->id
        );

        $this->response->assertStatus(404);
    }
}
