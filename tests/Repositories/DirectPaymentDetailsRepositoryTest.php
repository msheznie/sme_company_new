<?php namespace Tests\Repositories;

use App\Models\DirectPaymentDetails;
use App\Repositories\DirectPaymentDetailsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class DirectPaymentDetailsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var DirectPaymentDetailsRepository
     */
    protected $directPaymentDetailsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->directPaymentDetailsRepo = \App::make(DirectPaymentDetailsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_direct_payment_details()
    {
        $directPaymentDetails = DirectPaymentDetails::factory()->make()->toArray();

        $createdDirectPaymentDetails = $this->directPaymentDetailsRepo->create($directPaymentDetails);

        $createdDirectPaymentDetails = $createdDirectPaymentDetails->toArray();
        $this->assertArrayHasKey('id', $createdDirectPaymentDetails);
        $this->assertNotNull($createdDirectPaymentDetails['id'], 'Created DirectPaymentDetails must have id specified');
        $this->assertNotNull(DirectPaymentDetails::find($createdDirectPaymentDetails['id']), 'DirectPaymentDetails with given id must be in DB');
        $this->assertModelData($directPaymentDetails, $createdDirectPaymentDetails);
    }

    /**
     * @test read
     */
    public function test_read_direct_payment_details()
    {
        $directPaymentDetails = DirectPaymentDetails::factory()->create();

        $dbDirectPaymentDetails = $this->directPaymentDetailsRepo->find($directPaymentDetails->id);

        $dbDirectPaymentDetails = $dbDirectPaymentDetails->toArray();
        $this->assertModelData($directPaymentDetails->toArray(), $dbDirectPaymentDetails);
    }

    /**
     * @test update
     */
    public function test_update_direct_payment_details()
    {
        $directPaymentDetails = DirectPaymentDetails::factory()->create();
        $fakeDirectPaymentDetails = DirectPaymentDetails::factory()->make()->toArray();

        $updatedDirectPaymentDetails = $this->directPaymentDetailsRepo->update($fakeDirectPaymentDetails, $directPaymentDetails->id);

        $this->assertModelData($fakeDirectPaymentDetails, $updatedDirectPaymentDetails->toArray());
        $dbDirectPaymentDetails = $this->directPaymentDetailsRepo->find($directPaymentDetails->id);
        $this->assertModelData($fakeDirectPaymentDetails, $dbDirectPaymentDetails->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_direct_payment_details()
    {
        $directPaymentDetails = DirectPaymentDetails::factory()->create();

        $resp = $this->directPaymentDetailsRepo->delete($directPaymentDetails->id);

        $this->assertTrue($resp);
        $this->assertNull(DirectPaymentDetails::find($directPaymentDetails->id), 'DirectPaymentDetails should not exist in DB');
    }
}
