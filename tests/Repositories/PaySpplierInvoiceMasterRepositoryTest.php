<?php namespace Tests\Repositories;

use App\Models\PaySpplierInvoiceMaster;
use App\Repositories\PaySpplierInvoiceMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PaySpplierInvoiceMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var PaySpplierInvoiceMasterRepository
     */
    protected $paySpplierInvoiceMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->paySpplierInvoiceMasterRepo = \App::make(PaySpplierInvoiceMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_pay_spplier_invoice_master()
    {
        $paySpplierInvoiceMaster = PaySpplierInvoiceMaster::factory()->make()->toArray();

        $createdPaySpplierInvoiceMaster = $this->paySpplierInvoiceMasterRepo->create($paySpplierInvoiceMaster);

        $createdPaySpplierInvoiceMaster = $createdPaySpplierInvoiceMaster->toArray();
        $this->assertArrayHasKey('id', $createdPaySpplierInvoiceMaster);
        $this->assertNotNull($createdPaySpplierInvoiceMaster['id'], 'Created PaySpplierInvoiceMaster must have id specified');
        $this->assertNotNull(PaySpplierInvoiceMaster::find($createdPaySpplierInvoiceMaster['id']), 'PaySpplierInvoiceMaster with given id must be in DB');
        $this->assertModelData($paySpplierInvoiceMaster, $createdPaySpplierInvoiceMaster);
    }

    /**
     * @test read
     */
    public function test_read_pay_spplier_invoice_master()
    {
        $paySpplierInvoiceMaster = PaySpplierInvoiceMaster::factory()->create();

        $dbPaySpplierInvoiceMaster = $this->paySpplierInvoiceMasterRepo->find($paySpplierInvoiceMaster->id);

        $dbPaySpplierInvoiceMaster = $dbPaySpplierInvoiceMaster->toArray();
        $this->assertModelData($paySpplierInvoiceMaster->toArray(), $dbPaySpplierInvoiceMaster);
    }

    /**
     * @test update
     */
    public function test_update_pay_spplier_invoice_master()
    {
        $paySpplierInvoiceMaster = PaySpplierInvoiceMaster::factory()->create();
        $fakePaySpplierInvoiceMaster = PaySpplierInvoiceMaster::factory()->make()->toArray();

        $updatedPaySpplierInvoiceMaster = $this->paySpplierInvoiceMasterRepo->update($fakePaySpplierInvoiceMaster, $paySpplierInvoiceMaster->id);

        $this->assertModelData($fakePaySpplierInvoiceMaster, $updatedPaySpplierInvoiceMaster->toArray());
        $dbPaySpplierInvoiceMaster = $this->paySpplierInvoiceMasterRepo->find($paySpplierInvoiceMaster->id);
        $this->assertModelData($fakePaySpplierInvoiceMaster, $dbPaySpplierInvoiceMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_pay_spplier_invoice_master()
    {
        $paySpplierInvoiceMaster = PaySpplierInvoiceMaster::factory()->create();

        $resp = $this->paySpplierInvoiceMasterRepo->delete($paySpplierInvoiceMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(PaySpplierInvoiceMaster::find($paySpplierInvoiceMaster->id), 'PaySpplierInvoiceMaster should not exist in DB');
    }
}
