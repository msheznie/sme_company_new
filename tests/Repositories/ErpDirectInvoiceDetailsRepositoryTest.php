<?php namespace Tests\Repositories;

use App\Models\ErpDirectInvoiceDetails;
use App\Repositories\ErpDirectInvoiceDetailsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ErpDirectInvoiceDetailsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ErpDirectInvoiceDetailsRepository
     */
    protected $erpDirectInvoiceDetailsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->erpDirectInvoiceDetailsRepo = \App::make(ErpDirectInvoiceDetailsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_erp_direct_invoice_details()
    {
        $erpDirectInvoiceDetails = ErpDirectInvoiceDetails::factory()->make()->toArray();

        $createdErpDirectInvoiceDetails = $this->erpDirectInvoiceDetailsRepo->create($erpDirectInvoiceDetails);

        $createdErpDirectInvoiceDetails = $createdErpDirectInvoiceDetails->toArray();
        $this->assertArrayHasKey('id', $createdErpDirectInvoiceDetails);
        $this->assertNotNull($createdErpDirectInvoiceDetails['id'], 'Created ErpDirectInvoiceDetails must have id specified');
        $this->assertNotNull(ErpDirectInvoiceDetails::find($createdErpDirectInvoiceDetails['id']), 'ErpDirectInvoiceDetails with given id must be in DB');
        $this->assertModelData($erpDirectInvoiceDetails, $createdErpDirectInvoiceDetails);
    }

    /**
     * @test read
     */
    public function test_read_erp_direct_invoice_details()
    {
        $erpDirectInvoiceDetails = ErpDirectInvoiceDetails::factory()->create();

        $dbErpDirectInvoiceDetails = $this->erpDirectInvoiceDetailsRepo->find($erpDirectInvoiceDetails->id);

        $dbErpDirectInvoiceDetails = $dbErpDirectInvoiceDetails->toArray();
        $this->assertModelData($erpDirectInvoiceDetails->toArray(), $dbErpDirectInvoiceDetails);
    }

    /**
     * @test update
     */
    public function test_update_erp_direct_invoice_details()
    {
        $erpDirectInvoiceDetails = ErpDirectInvoiceDetails::factory()->create();
        $fakeErpDirectInvoiceDetails = ErpDirectInvoiceDetails::factory()->make()->toArray();

        $updatedErpDirectInvoiceDetails = $this->erpDirectInvoiceDetailsRepo->update($fakeErpDirectInvoiceDetails, $erpDirectInvoiceDetails->id);

        $this->assertModelData($fakeErpDirectInvoiceDetails, $updatedErpDirectInvoiceDetails->toArray());
        $dbErpDirectInvoiceDetails = $this->erpDirectInvoiceDetailsRepo->find($erpDirectInvoiceDetails->id);
        $this->assertModelData($fakeErpDirectInvoiceDetails, $dbErpDirectInvoiceDetails->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_erp_direct_invoice_details()
    {
        $erpDirectInvoiceDetails = ErpDirectInvoiceDetails::factory()->create();

        $resp = $this->erpDirectInvoiceDetailsRepo->delete($erpDirectInvoiceDetails->id);

        $this->assertTrue($resp);
        $this->assertNull(ErpDirectInvoiceDetails::find($erpDirectInvoiceDetails->id), 'ErpDirectInvoiceDetails should not exist in DB');
    }
}
