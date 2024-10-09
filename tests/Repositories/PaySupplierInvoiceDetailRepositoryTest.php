<?php namespace Tests\Repositories;

use App\Models\PaySupplierInvoiceDetail;
use App\Repositories\PaySupplierInvoiceDetailRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PaySupplierInvoiceDetailRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var PaySupplierInvoiceDetailRepository
     */
    protected $paySupplierInvoiceDetailRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->paySupplierInvoiceDetailRepo = \App::make(PaySupplierInvoiceDetailRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_pay_supplier_invoice_detail()
    {
        $paySupplierInvoiceDetail = PaySupplierInvoiceDetail::factory()->make()->toArray();

        $createdPaySupplierInvoiceDetail = $this->paySupplierInvoiceDetailRepo->create($paySupplierInvoiceDetail);

        $createdPaySupplierInvoiceDetail = $createdPaySupplierInvoiceDetail->toArray();
        $this->assertArrayHasKey('id', $createdPaySupplierInvoiceDetail);
        $this->assertNotNull($createdPaySupplierInvoiceDetail['id'], 'Created PaySupplierInvoiceDetail must have id specified');
        $this->assertNotNull(PaySupplierInvoiceDetail::find($createdPaySupplierInvoiceDetail['id']), 'PaySupplierInvoiceDetail with given id must be in DB');
        $this->assertModelData($paySupplierInvoiceDetail, $createdPaySupplierInvoiceDetail);
    }

    /**
     * @test read
     */
    public function test_read_pay_supplier_invoice_detail()
    {
        $paySupplierInvoiceDetail = PaySupplierInvoiceDetail::factory()->create();

        $dbPaySupplierInvoiceDetail = $this->paySupplierInvoiceDetailRepo->find($paySupplierInvoiceDetail->id);

        $dbPaySupplierInvoiceDetail = $dbPaySupplierInvoiceDetail->toArray();
        $this->assertModelData($paySupplierInvoiceDetail->toArray(), $dbPaySupplierInvoiceDetail);
    }

    /**
     * @test update
     */
    public function test_update_pay_supplier_invoice_detail()
    {
        $paySupplierInvoiceDetail = PaySupplierInvoiceDetail::factory()->create();
        $fakePaySupplierInvoiceDetail = PaySupplierInvoiceDetail::factory()->make()->toArray();

        $updatedPaySupplierInvoiceDetail = $this->paySupplierInvoiceDetailRepo->update($fakePaySupplierInvoiceDetail, $paySupplierInvoiceDetail->id);

        $this->assertModelData($fakePaySupplierInvoiceDetail, $updatedPaySupplierInvoiceDetail->toArray());
        $dbPaySupplierInvoiceDetail = $this->paySupplierInvoiceDetailRepo->find($paySupplierInvoiceDetail->id);
        $this->assertModelData($fakePaySupplierInvoiceDetail, $dbPaySupplierInvoiceDetail->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_pay_supplier_invoice_detail()
    {
        $paySupplierInvoiceDetail = PaySupplierInvoiceDetail::factory()->create();

        $resp = $this->paySupplierInvoiceDetailRepo->delete($paySupplierInvoiceDetail->id);

        $this->assertTrue($resp);
        $this->assertNull(PaySupplierInvoiceDetail::find($paySupplierInvoiceDetail->id), 'PaySupplierInvoiceDetail should not exist in DB');
    }
}
