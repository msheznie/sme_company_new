<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\SupplierContactDetails;

class SupplierContactDetailsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_supplier_contact_details()
    {
        $supplierContactDetails = SupplierContactDetails::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/supplier_contact_details', $supplierContactDetails
        );

        $this->assertApiResponse($supplierContactDetails);
    }

    /**
     * @test
     */
    public function test_read_supplier_contact_details()
    {
        $supplierContactDetails = SupplierContactDetails::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/supplier_contact_details/'.$supplierContactDetails->id
        );

        $this->assertApiResponse($supplierContactDetails->toArray());
    }

    /**
     * @test
     */
    public function test_update_supplier_contact_details()
    {
        $supplierContactDetails = SupplierContactDetails::factory()->create();
        $editedSupplierContactDetails = SupplierContactDetails::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/supplier_contact_details/'.$supplierContactDetails->id,
            $editedSupplierContactDetails
        );

        $this->assertApiResponse($editedSupplierContactDetails);
    }

    /**
     * @test
     */
    public function test_delete_supplier_contact_details()
    {
        $supplierContactDetails = SupplierContactDetails::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/supplier_contact_details/'.$supplierContactDetails->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/supplier_contact_details/'.$supplierContactDetails->id
        );

        $this->response->assertStatus(404);
    }
}
