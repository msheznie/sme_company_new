<?php namespace Tests\Repositories;

use App\Models\TimeMaterialConsumption;
use App\Repositories\TimeMaterialConsumptionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TimeMaterialConsumptionRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TimeMaterialConsumptionRepository
     */
    protected $timeMaterialConsumptionRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->timeMaterialConsumptionRepo = \App::make(TimeMaterialConsumptionRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_time_material_consumption()
    {
        $timeMaterialConsumption = TimeMaterialConsumption::factory()->make()->toArray();

        $createdTimeMaterialConsumption = $this->timeMaterialConsumptionRepo->create($timeMaterialConsumption);

        $createdTimeMaterialConsumption = $createdTimeMaterialConsumption->toArray();
        $this->assertArrayHasKey('id', $createdTimeMaterialConsumption);
        $this->assertNotNull($createdTimeMaterialConsumption['id'],
            'Created TimeMaterialConsumption must have id specified');
        $this->assertNotNull(TimeMaterialConsumption::find($createdTimeMaterialConsumption['id']),
            'TimeMaterialConsumption with given id must be in DB');
        $this->assertModelData($timeMaterialConsumption, $createdTimeMaterialConsumption);
    }

    /**
     * @test read
     */
    public function test_read_time_material_consumption()
    {
        $timeMaterialConsumption = TimeMaterialConsumption::factory()->create();

        $dbTimeMaterialConsumption = $this->timeMaterialConsumptionRepo->find($timeMaterialConsumption->id);

        $dbTimeMaterialConsumption = $dbTimeMaterialConsumption->toArray();
        $this->assertModelData($timeMaterialConsumption->toArray(), $dbTimeMaterialConsumption);
    }

    /**
     * @test update
     */
    public function test_update_time_material_consumption()
    {
        $timeMaterialConsumption = TimeMaterialConsumption::factory()->create();
        $fakeTimeMaterialConsumption = TimeMaterialConsumption::factory()->make()->toArray();

        $updatedTimeMaterialConsumption = $this->timeMaterialConsumptionRepo->update($fakeTimeMaterialConsumption,
            $timeMaterialConsumption->id);

        $this->assertModelData($fakeTimeMaterialConsumption, $updatedTimeMaterialConsumption->toArray());
        $dbTimeMaterialConsumption = $this->timeMaterialConsumptionRepo->find($timeMaterialConsumption->id);
        $this->assertModelData($fakeTimeMaterialConsumption, $dbTimeMaterialConsumption->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_time_material_consumption()
    {
        $timeMaterialConsumption = TimeMaterialConsumption::factory()->create();

        $resp = $this->timeMaterialConsumptionRepo->delete($timeMaterialConsumption->id);

        $this->assertTrue($resp);
        $this->assertNull(TimeMaterialConsumption::find($timeMaterialConsumption->id),
            'TimeMaterialConsumption should not exist in DB');
    }
}
