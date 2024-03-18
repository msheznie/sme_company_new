<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CustomerMaster;

class CustomerMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_customer_master()
    {
        $customerMaster = CustomerMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/customer_masters', $customerMaster
        );

        $this->assertApiResponse($customerMaster);
    }

    /**
     * @test
     */
    public function test_read_customer_master()
    {
        $customerMaster = CustomerMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/customer_masters/'.$customerMaster->id
        );

        $this->assertApiResponse($customerMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_customer_master()
    {
        $customerMaster = CustomerMaster::factory()->create();
        $editedCustomerMaster = CustomerMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/customer_masters/'.$customerMaster->id,
            $editedCustomerMaster
        );

        $this->assertApiResponse($editedCustomerMaster);
    }

    /**
     * @test
     */
    public function test_delete_customer_master()
    {
        $customerMaster = CustomerMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/customer_masters/'.$customerMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/customer_masters/'.$customerMaster->id
        );

        $this->response->assertStatus(404);
    }
}
