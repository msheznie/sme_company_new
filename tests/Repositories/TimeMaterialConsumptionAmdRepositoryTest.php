<?php namespace Tests\Repositories;

use App\Models\TimeMaterialConsumptionAmd;
use App\Repositories\TimeMaterialConsumptionAmdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TimeMaterialConsumptionAmdRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TimeMaterialConsumptionAmdRepository
     */
    protected $timeMaterialConsumptionAmdRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->timeMaterialConsumptionAmdRepo = \App::make(TimeMaterialConsumptionAmdRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_time_material_consumption_amd()
    {
        $timeMaterialConsumptionAmd = TimeMaterialConsumptionAmd::factory()->make()->toArray();

        $createdTimeMaterialConsumptionAmd = $this->timeMaterialConsumptionAmdRepo->create($timeMaterialConsumptionAmd);

        $createdTimeMaterialConsumptionAmd = $createdTimeMaterialConsumptionAmd->toArray();
        $this->assertArrayHasKey('id', $createdTimeMaterialConsumptionAmd);
        $this->assertNotNull($createdTimeMaterialConsumptionAmd['id'], 'Created TimeMaterialConsumptionAmd must have id specified');
        $this->assertNotNull(TimeMaterialConsumptionAmd::find($createdTimeMaterialConsumptionAmd['id']), 'TimeMaterialConsumptionAmd with given id must be in DB');
        $this->assertModelData($timeMaterialConsumptionAmd, $createdTimeMaterialConsumptionAmd);
    }

    /**
     * @test read
     */
    public function test_read_time_material_consumption_amd()
    {
        $timeMaterialConsumptionAmd = TimeMaterialConsumptionAmd::factory()->create();

        $dbTimeMaterialConsumptionAmd = $this->timeMaterialConsumptionAmdRepo->find($timeMaterialConsumptionAmd->id);

        $dbTimeMaterialConsumptionAmd = $dbTimeMaterialConsumptionAmd->toArray();
        $this->assertModelData($timeMaterialConsumptionAmd->toArray(), $dbTimeMaterialConsumptionAmd);
    }

    /**
     * @test update
     */
    public function test_update_time_material_consumption_amd()
    {
        $timeMaterialConsumptionAmd = TimeMaterialConsumptionAmd::factory()->create();
        $fakeTimeMaterialConsumptionAmd = TimeMaterialConsumptionAmd::factory()->make()->toArray();

        $updatedTimeMaterialConsumptionAmd = $this->timeMaterialConsumptionAmdRepo->update($fakeTimeMaterialConsumptionAmd, $timeMaterialConsumptionAmd->id);

        $this->assertModelData($fakeTimeMaterialConsumptionAmd, $updatedTimeMaterialConsumptionAmd->toArray());
        $dbTimeMaterialConsumptionAmd = $this->timeMaterialConsumptionAmdRepo->find($timeMaterialConsumptionAmd->id);
        $this->assertModelData($fakeTimeMaterialConsumptionAmd, $dbTimeMaterialConsumptionAmd->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_time_material_consumption_amd()
    {
        $timeMaterialConsumptionAmd = TimeMaterialConsumptionAmd::factory()->create();

        $resp = $this->timeMaterialConsumptionAmdRepo->delete($timeMaterialConsumptionAmd->id);

        $this->assertTrue($resp);
        $this->assertNull(TimeMaterialConsumptionAmd::find($timeMaterialConsumptionAmd->id), 'TimeMaterialConsumptionAmd should not exist in DB');
    }
}
