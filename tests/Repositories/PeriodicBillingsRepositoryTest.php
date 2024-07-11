<?php namespace Tests\Repositories;

use App\Models\PeriodicBillings;
use App\Repositories\PeriodicBillingsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PeriodicBillingsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var PeriodicBillingsRepository
     */
    protected $periodicBillingsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->periodicBillingsRepo = \App::make(PeriodicBillingsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_periodic_billings()
    {
        $periodicBillings = PeriodicBillings::factory()->make()->toArray();

        $createdPeriodicBillings = $this->periodicBillingsRepo->create($periodicBillings);

        $createdPeriodicBillings = $createdPeriodicBillings->toArray();
        $this->assertArrayHasKey('id', $createdPeriodicBillings);
        $this->assertNotNull($createdPeriodicBillings['id'],
            'Created PeriodicBillings must have id specified');
        $this->assertNotNull(PeriodicBillings::find($createdPeriodicBillings['id']),
            'PeriodicBillings with given id must be in DB');
        $this->assertModelData($periodicBillings, $createdPeriodicBillings);
    }

    /**
     * @test read
     */
    public function test_read_periodic_billings()
    {
        $periodicBillings = PeriodicBillings::factory()->create();

        $dbPeriodicBillings = $this->periodicBillingsRepo->find($periodicBillings->id);

        $dbPeriodicBillings = $dbPeriodicBillings->toArray();
        $this->assertModelData($periodicBillings->toArray(), $dbPeriodicBillings);
    }

    /**
     * @test update
     */
    public function test_update_periodic_billings()
    {
        $periodicBillings = PeriodicBillings::factory()->create();
        $fakePeriodicBillings = PeriodicBillings::factory()->make()->toArray();

        $updatedPeriodicBillings = $this->periodicBillingsRepo->update($fakePeriodicBillings, $periodicBillings->id);

        $this->assertModelData($fakePeriodicBillings, $updatedPeriodicBillings->toArray());
        $dbPeriodicBillings = $this->periodicBillingsRepo->find($periodicBillings->id);
        $this->assertModelData($fakePeriodicBillings, $dbPeriodicBillings->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_periodic_billings()
    {
        $periodicBillings = PeriodicBillings::factory()->create();

        $resp = $this->periodicBillingsRepo->delete($periodicBillings->id);

        $this->assertTrue($resp);
        $this->assertNull(PeriodicBillings::find($periodicBillings->id),
            'PeriodicBillings should not exist in DB');
    }
}
