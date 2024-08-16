<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ErpDirectInvoiceDetails;

class ErpDirectInvoiceDetailsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_erp_direct_invoice_details()
    {
        $erpDirectInvoiceDetails = ErpDirectInvoiceDetails::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/erp_direct_invoice_details', $erpDirectInvoiceDetails
        );

        $this->assertApiResponse($erpDirectInvoiceDetails);
    }

    /**
     * @test
     */
    public function test_read_erp_direct_invoice_details()
    {
        $erpDirectInvoiceDetails = ErpDirectInvoiceDetails::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/erp_direct_invoice_details/'.$erpDirectInvoiceDetails->id
        );

        $this->assertApiResponse($erpDirectInvoiceDetails->toArray());
    }

    /**
     * @test
     */
    public function test_update_erp_direct_invoice_details()
    {
        $erpDirectInvoiceDetails = ErpDirectInvoiceDetails::factory()->create();
        $editedErpDirectInvoiceDetails = ErpDirectInvoiceDetails::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/erp_direct_invoice_details/'.$erpDirectInvoiceDetails->id,
            $editedErpDirectInvoiceDetails
        );

        $this->assertApiResponse($editedErpDirectInvoiceDetails);
    }

    /**
     * @test
     */
    public function test_delete_erp_direct_invoice_details()
    {
        $erpDirectInvoiceDetails = ErpDirectInvoiceDetails::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/erp_direct_invoice_details/'.$erpDirectInvoiceDetails->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/erp_direct_invoice_details/'.$erpDirectInvoiceDetails->id
        );

        $this->response->assertStatus(404);
    }
}
