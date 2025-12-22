<?php namespace Tests\Repositories;

use App\Models\SupplierContactDetails;
use App\Repositories\SupplierContactDetailsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class SupplierContactDetailsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var SupplierContactDetailsRepository
     */
    protected $supplierContactDetailsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->supplierContactDetailsRepo = \App::make(SupplierContactDetailsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_supplier_contact_details()
    {
        $supplierContactDetails = SupplierContactDetails::factory()->make()->toArray();

        $createdSupplierContactDetails = $this->supplierContactDetailsRepo->create($supplierContactDetails);

        $createdSupplierContactDetails = $createdSupplierContactDetails->toArray();
        $this->assertArrayHasKey('id', $createdSupplierContactDetails);
        $this->assertNotNull($createdSupplierContactDetails['id'], 'Created SupplierContactDetails must have id specified');
        $this->assertNotNull(SupplierContactDetails::find($createdSupplierContactDetails['id']), 'SupplierContactDetails with given id must be in DB');
        $this->assertModelData($supplierContactDetails, $createdSupplierContactDetails);
    }

    /**
     * @test read
     */
    public function test_read_supplier_contact_details()
    {
        $supplierContactDetails = SupplierContactDetails::factory()->create();

        $dbSupplierContactDetails = $this->supplierContactDetailsRepo->find($supplierContactDetails->id);

        $dbSupplierContactDetails = $dbSupplierContactDetails->toArray();
        $this->assertModelData($supplierContactDetails->toArray(), $dbSupplierContactDetails);
    }

    /**
     * @test update
     */
    public function test_update_supplier_contact_details()
    {
        $supplierContactDetails = SupplierContactDetails::factory()->create();
        $fakeSupplierContactDetails = SupplierContactDetails::factory()->make()->toArray();

        $updatedSupplierContactDetails = $this->supplierContactDetailsRepo->update($fakeSupplierContactDetails, $supplierContactDetails->id);

        $this->assertModelData($fakeSupplierContactDetails, $updatedSupplierContactDetails->toArray());
        $dbSupplierContactDetails = $this->supplierContactDetailsRepo->find($supplierContactDetails->id);
        $this->assertModelData($fakeSupplierContactDetails, $dbSupplierContactDetails->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_supplier_contact_details()
    {
        $supplierContactDetails = SupplierContactDetails::factory()->create();

        $resp = $this->supplierContactDetailsRepo->delete($supplierContactDetails->id);

        $this->assertTrue($resp);
        $this->assertNull(SupplierContactDetails::find($supplierContactDetails->id), 'SupplierContactDetails should not exist in DB');
    }
}
