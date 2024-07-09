<?php namespace Tests\Repositories;

use App\Models\SystemConfigurationDetail;
use App\Repositories\SystemConfigurationDetailRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class SystemConfigurationDetailRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var SystemConfigurationDetailRepository
     */
    protected $systemConfigurationDetailRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->systemConfigurationDetailRepo = \App::make(SystemConfigurationDetailRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_system_configuration_detail()
    {
        $systemConfigurationDetail = SystemConfigurationDetail::factory()->make()->toArray();

        $createdSystemConfigurationDetail = $this->systemConfigurationDetailRepo->create($systemConfigurationDetail);

        $createdSystemConfigurationDetail = $createdSystemConfigurationDetail->toArray();
        $this->assertArrayHasKey('id', $createdSystemConfigurationDetail);
        $this->assertNotNull($createdSystemConfigurationDetail['id'], 'Created SystemConfigurationDetail must have id specified');
        $this->assertNotNull(SystemConfigurationDetail::find($createdSystemConfigurationDetail['id']), 'SystemConfigurationDetail with given id must be in DB');
        $this->assertModelData($systemConfigurationDetail, $createdSystemConfigurationDetail);
    }

    /**
     * @test read
     */
    public function test_read_system_configuration_detail()
    {
        $systemConfigurationDetail = SystemConfigurationDetail::factory()->create();

        $dbSystemConfigurationDetail = $this->systemConfigurationDetailRepo->find($systemConfigurationDetail->id);

        $dbSystemConfigurationDetail = $dbSystemConfigurationDetail->toArray();
        $this->assertModelData($systemConfigurationDetail->toArray(), $dbSystemConfigurationDetail);
    }

    /**
     * @test update
     */
    public function test_update_system_configuration_detail()
    {
        $systemConfigurationDetail = SystemConfigurationDetail::factory()->create();
        $fakeSystemConfigurationDetail = SystemConfigurationDetail::factory()->make()->toArray();

        $updatedSystemConfigurationDetail = $this->systemConfigurationDetailRepo->update($fakeSystemConfigurationDetail, $systemConfigurationDetail->id);

        $this->assertModelData($fakeSystemConfigurationDetail, $updatedSystemConfigurationDetail->toArray());
        $dbSystemConfigurationDetail = $this->systemConfigurationDetailRepo->find($systemConfigurationDetail->id);
        $this->assertModelData($fakeSystemConfigurationDetail, $dbSystemConfigurationDetail->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_system_configuration_detail()
    {
        $systemConfigurationDetail = SystemConfigurationDetail::factory()->create();

        $resp = $this->systemConfigurationDetailRepo->delete($systemConfigurationDetail->id);

        $this->assertTrue($resp);
        $this->assertNull(SystemConfigurationDetail::find($systemConfigurationDetail->id), 'SystemConfigurationDetail should not exist in DB');
    }
}
