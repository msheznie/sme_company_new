<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\BillingFrequencies;

class BillingFrequenciesApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;
    protected $url = '/api/billing_frequencies/';

    /**
     * @test
     */
    public function test_create_billing_frequencies()
    {
        $billingFrequencies = BillingFrequencies::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/billing_frequencies', $billingFrequencies
        );

        $this->assertApiResponse($billingFrequencies);
    }

    /**
     * @test
     */
    public function test_read_billing_frequencies()
    {
        $billingFrequencies = BillingFrequencies::factory()->create();

        $this->response = $this->json(
            'GET',
            $this->url.$billingFrequencies->id
        );

        $this->assertApiResponse($billingFrequencies->toArray());
    }

    /**
     * @test
     */
    public function test_update_billing_frequencies()
    {
        $billingFrequencies = BillingFrequencies::factory()->create();
        $editedBillingFrequencies = BillingFrequencies::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            $this->url.$billingFrequencies->id,
            $editedBillingFrequencies
        );

        $this->assertApiResponse($editedBillingFrequencies);
    }

    /**
     * @test
     */
    public function test_delete_billing_frequencies()
    {
        $billingFrequencies = BillingFrequencies::factory()->create();

        $this->response = $this->json(
            'DELETE',
            $this->url.$billingFrequencies->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            $this->url.$billingFrequencies->id
        );

        $this->response->assertStatus(404);
    }
}
