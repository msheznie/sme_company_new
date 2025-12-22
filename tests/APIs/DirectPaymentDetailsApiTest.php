<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\DirectPaymentDetails;

class DirectPaymentDetailsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_direct_payment_details()
    {
        $directPaymentDetails = DirectPaymentDetails::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/direct_payment_details', $directPaymentDetails
        );

        $this->assertApiResponse($directPaymentDetails);
    }

    /**
     * @test
     */
    public function test_read_direct_payment_details()
    {
        $directPaymentDetails = DirectPaymentDetails::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/direct_payment_details/'.$directPaymentDetails->id
        );

        $this->assertApiResponse($directPaymentDetails->toArray());
    }

    /**
     * @test
     */
    public function test_update_direct_payment_details()
    {
        $directPaymentDetails = DirectPaymentDetails::factory()->create();
        $editedDirectPaymentDetails = DirectPaymentDetails::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/direct_payment_details/'.$directPaymentDetails->id,
            $editedDirectPaymentDetails
        );

        $this->assertApiResponse($editedDirectPaymentDetails);
    }

    /**
     * @test
     */
    public function test_delete_direct_payment_details()
    {
        $directPaymentDetails = DirectPaymentDetails::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/direct_payment_details/'.$directPaymentDetails->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/direct_payment_details/'.$directPaymentDetails->id
        );

        $this->response->assertStatus(404);
    }
}
