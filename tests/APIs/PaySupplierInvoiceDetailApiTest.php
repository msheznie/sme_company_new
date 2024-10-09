<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\PaySupplierInvoiceDetail;

class PaySupplierInvoiceDetailApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_pay_supplier_invoice_detail()
    {
        $paySupplierInvoiceDetail = PaySupplierInvoiceDetail::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/pay_supplier_invoice_details', $paySupplierInvoiceDetail
        );

        $this->assertApiResponse($paySupplierInvoiceDetail);
    }

    /**
     * @test
     */
    public function test_read_pay_supplier_invoice_detail()
    {
        $paySupplierInvoiceDetail = PaySupplierInvoiceDetail::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/pay_supplier_invoice_details/'.$paySupplierInvoiceDetail->id
        );

        $this->assertApiResponse($paySupplierInvoiceDetail->toArray());
    }

    /**
     * @test
     */
    public function test_update_pay_supplier_invoice_detail()
    {
        $paySupplierInvoiceDetail = PaySupplierInvoiceDetail::factory()->create();
        $editedPaySupplierInvoiceDetail = PaySupplierInvoiceDetail::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/pay_supplier_invoice_details/'.$paySupplierInvoiceDetail->id,
            $editedPaySupplierInvoiceDetail
        );

        $this->assertApiResponse($editedPaySupplierInvoiceDetail);
    }

    /**
     * @test
     */
    public function test_delete_pay_supplier_invoice_detail()
    {
        $paySupplierInvoiceDetail = PaySupplierInvoiceDetail::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/pay_supplier_invoice_details/'.$paySupplierInvoiceDetail->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/pay_supplier_invoice_details/'.$paySupplierInvoiceDetail->id
        );

        $this->response->assertStatus(404);
    }
}
