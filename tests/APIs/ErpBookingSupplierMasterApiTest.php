<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ErpBookingSupplierMaster;

class ErpBookingSupplierMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_erp_booking_supplier_master()
    {
        $erpBookingSupplierMaster = ErpBookingSupplierMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/erp_booking_supplier_masters', $erpBookingSupplierMaster
        );

        $this->assertApiResponse($erpBookingSupplierMaster);
    }

    /**
     * @test
     */
    public function test_read_erp_booking_supplier_master()
    {
        $erpBookingSupplierMaster = ErpBookingSupplierMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/erp_booking_supplier_masters/'.$erpBookingSupplierMaster->id
        );

        $this->assertApiResponse($erpBookingSupplierMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_erp_booking_supplier_master()
    {
        $erpBookingSupplierMaster = ErpBookingSupplierMaster::factory()->create();
        $editedErpBookingSupplierMaster = ErpBookingSupplierMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/erp_booking_supplier_masters/'.$erpBookingSupplierMaster->id,
            $editedErpBookingSupplierMaster
        );

        $this->assertApiResponse($editedErpBookingSupplierMaster);
    }

    /**
     * @test
     */
    public function test_delete_erp_booking_supplier_master()
    {
        $erpBookingSupplierMaster = ErpBookingSupplierMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/erp_booking_supplier_masters/'.$erpBookingSupplierMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/erp_booking_supplier_masters/'.$erpBookingSupplierMaster->id
        );

        $this->response->assertStatus(404);
    }
}
