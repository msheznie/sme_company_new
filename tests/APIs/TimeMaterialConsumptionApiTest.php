<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\TimeMaterialConsumption;

class TimeMaterialConsumptionApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;
    protected $url = '/api/time_material_consumptions/';

    /**
     * @test
     */
    public function test_create_time_material_consumption()
    {
        $timeMaterialConsumption = TimeMaterialConsumption::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/time_material_consumptions', $timeMaterialConsumption
        );

        $this->assertApiResponse($timeMaterialConsumption);
    }

    /**
     * @test
     */
    public function test_read_time_material_consumption()
    {
        $timeMaterialConsumption = TimeMaterialConsumption::factory()->create();

        $this->response = $this->json(
            'GET',
            $this->url.$timeMaterialConsumption->id
        );

        $this->assertApiResponse($timeMaterialConsumption->toArray());
    }

    /**
     * @test
     */
    public function test_update_time_material_consumption()
    {
        $timeMaterialConsumption = TimeMaterialConsumption::factory()->create();
        $editedTimeMaterialConsumption = TimeMaterialConsumption::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            $this->url.$timeMaterialConsumption->id,
            $editedTimeMaterialConsumption
        );

        $this->assertApiResponse($editedTimeMaterialConsumption);
    }

    /**
     * @test
     */
    public function test_delete_time_material_consumption()
    {
        $timeMaterialConsumption = TimeMaterialConsumption::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/time_material_consumptions/'.$timeMaterialConsumption->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            $this->url.$timeMaterialConsumption->id
        );

        $this->response->assertStatus(404);
    }
}
