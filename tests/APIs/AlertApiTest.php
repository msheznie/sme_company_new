<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Alert;

class AlertApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_alert()
    {
        $alert = Alert::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/alerts', $alert
        );

        $this->assertApiResponse($alert);
    }

    /**
     * @test
     */
    public function test_read_alert()
    {
        $alert = Alert::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/alerts/'.$alert->id
        );

        $this->assertApiResponse($alert->toArray());
    }

    /**
     * @test
     */
    public function test_update_alert()
    {
        $alert = Alert::factory()->create();
        $editedAlert = Alert::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/alerts/'.$alert->id,
            $editedAlert
        );

        $this->assertApiResponse($editedAlert);
    }

    /**
     * @test
     */
    public function test_delete_alert()
    {
        $alert = Alert::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/alerts/'.$alert->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/alerts/'.$alert->id
        );

        $this->response->assertStatus(404);
    }
}
