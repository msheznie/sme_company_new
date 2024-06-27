<?php namespace Tests\Repositories;

use App\Models\BillingFrequencies;
use App\Repositories\BillingFrequenciesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class BillingFrequenciesRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var BillingFrequenciesRepository
     */
    protected $billingFrequenciesRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->billingFrequenciesRepo = \App::make(BillingFrequenciesRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_billing_frequencies()
    {
        $billingFrequencies = BillingFrequencies::factory()->make()->toArray();

        $createdBillingFrequencies = $this->billingFrequenciesRepo->create($billingFrequencies);

        $createdBillingFrequencies = $createdBillingFrequencies->toArray();
        $this->assertArrayHasKey('id', $createdBillingFrequencies);
        $this->assertNotNull($createdBillingFrequencies['id'],
            'Created BillingFrequencies must have id specified');
        $this->assertNotNull(BillingFrequencies::find($createdBillingFrequencies['id']),
            'BillingFrequencies with given id must be in DB');
        $this->assertModelData($billingFrequencies, $createdBillingFrequencies);
    }

    /**
     * @test read
     */
    public function test_read_billing_frequencies()
    {
        $billingFrequencies = BillingFrequencies::factory()->create();

        $dbBillingFrequencies = $this->billingFrequenciesRepo->find($billingFrequencies->id);

        $dbBillingFrequencies = $dbBillingFrequencies->toArray();
        $this->assertModelData($billingFrequencies->toArray(), $dbBillingFrequencies);
    }

    /**
     * @test update
     */
    public function test_update_billing_frequencies()
    {
        $billingFrequencies = BillingFrequencies::factory()->create();
        $fakeBillingFrequencies = BillingFrequencies::factory()->make()->toArray();

        $updatedBillingFrequencies = $this->billingFrequenciesRepo->update($fakeBillingFrequencies,
            $billingFrequencies->id);

        $this->assertModelData($fakeBillingFrequencies, $updatedBillingFrequencies->toArray());
        $dbBillingFrequencies = $this->billingFrequenciesRepo->find($billingFrequencies->id);
        $this->assertModelData($fakeBillingFrequencies, $dbBillingFrequencies->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_billing_frequencies()
    {
        $billingFrequencies = BillingFrequencies::factory()->create();

        $resp = $this->billingFrequenciesRepo->delete($billingFrequencies->id);

        $this->assertTrue($resp);
        $this->assertNull(BillingFrequencies::find($billingFrequencies->id),
            'BillingFrequencies should not exist in DB');
    }
}
