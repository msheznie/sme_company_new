<?php namespace Tests\Repositories;

use App\Models\CustomerMaster;
use App\Repositories\CustomerMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CustomerMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CustomerMasterRepository
     */
    protected $customerMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->customerMasterRepo = \App::make(CustomerMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_customer_master()
    {
        $customerMaster = CustomerMaster::factory()->make()->toArray();

        $createdCustomerMaster = $this->customerMasterRepo->create($customerMaster);

        $createdCustomerMaster = $createdCustomerMaster->toArray();
        $this->assertArrayHasKey('id', $createdCustomerMaster);
        $this->assertNotNull($createdCustomerMaster['id'], 'Created CustomerMaster must have id specified');
        $this->assertNotNull(CustomerMaster::find($createdCustomerMaster['id']), 'CustomerMaster with given id must be in DB');
        $this->assertModelData($customerMaster, $createdCustomerMaster);
    }

    /**
     * @test read
     */
    public function test_read_customer_master()
    {
        $customerMaster = CustomerMaster::factory()->create();

        $dbCustomerMaster = $this->customerMasterRepo->find($customerMaster->id);

        $dbCustomerMaster = $dbCustomerMaster->toArray();
        $this->assertModelData($customerMaster->toArray(), $dbCustomerMaster);
    }

    /**
     * @test update
     */
    public function test_update_customer_master()
    {
        $customerMaster = CustomerMaster::factory()->create();
        $fakeCustomerMaster = CustomerMaster::factory()->make()->toArray();

        $updatedCustomerMaster = $this->customerMasterRepo->update($fakeCustomerMaster, $customerMaster->id);

        $this->assertModelData($fakeCustomerMaster, $updatedCustomerMaster->toArray());
        $dbCustomerMaster = $this->customerMasterRepo->find($customerMaster->id);
        $this->assertModelData($fakeCustomerMaster, $dbCustomerMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_customer_master()
    {
        $customerMaster = CustomerMaster::factory()->create();

        $resp = $this->customerMasterRepo->delete($customerMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(CustomerMaster::find($customerMaster->id), 'CustomerMaster should not exist in DB');
    }
}
