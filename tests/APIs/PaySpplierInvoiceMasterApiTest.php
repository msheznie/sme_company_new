<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\PaySpplierInvoiceMaster;

class PaySpplierInvoiceMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_pay_spplier_invoice_master()
    {
        $paySpplierInvoiceMaster = PaySpplierInvoiceMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/pay_spplier_invoice_masters', $paySpplierInvoiceMaster
        );

        $this->assertApiResponse($paySpplierInvoiceMaster);
    }

    /**
     * @test
     */
    public function test_read_pay_spplier_invoice_master()
    {
        $paySpplierInvoiceMaster = PaySpplierInvoiceMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/pay_spplier_invoice_masters/'.$paySpplierInvoiceMaster->id
        );

        $this->assertApiResponse($paySpplierInvoiceMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_pay_spplier_invoice_master()
    {
        $paySpplierInvoiceMaster = PaySpplierInvoiceMaster::factory()->create();
        $editedPaySpplierInvoiceMaster = PaySpplierInvoiceMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/pay_spplier_invoice_masters/'.$paySpplierInvoiceMaster->id,
            $editedPaySpplierInvoiceMaster
        );

        $this->assertApiResponse($editedPaySpplierInvoiceMaster);
    }

    /**
     * @test
     */
    public function test_delete_pay_spplier_invoice_master()
    {
        $paySpplierInvoiceMaster = PaySpplierInvoiceMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/pay_spplier_invoice_masters/'.$paySpplierInvoiceMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/pay_spplier_invoice_masters/'.$paySpplierInvoiceMaster->id
        );

        $this->response->assertStatus(404);
    }
}
