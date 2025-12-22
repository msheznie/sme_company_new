<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\PeriodicBillings;

class PeriodicBillingsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;
    protected $url = '/api/periodic_billings/';
    /**
     * @test
     */
    public function test_create_periodic_billings()
    {
        $periodicBillings = PeriodicBillings::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/periodic_billings', $periodicBillings
        );

        $this->assertApiResponse($periodicBillings);
    }

    /**
     * @test
     */
    public function test_read_periodic_billings()
    {
        $periodicBillings = PeriodicBillings::factory()->create();

        $this->response = $this->json(
            'GET',
            $this->url.$periodicBillings->id
        );

        $this->assertApiResponse($periodicBillings->toArray());
    }

    /**
     * @test
     */
    public function test_update_periodic_billings()
    {
        $periodicBillings = PeriodicBillings::factory()->create();
        $editedPeriodicBillings = PeriodicBillings::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            $this->url.$periodicBillings->id,
            $editedPeriodicBillings
        );

        $this->assertApiResponse($editedPeriodicBillings);
    }

    /**
     * @test
     */
    public function test_delete_periodic_billings()
    {
        $periodicBillings = PeriodicBillings::factory()->create();

        $this->response = $this->json(
            'DELETE',
            $this->url.$periodicBillings->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            $this->url.$periodicBillings->id
        );

        $this->response->assertStatus(404);
    }
}
