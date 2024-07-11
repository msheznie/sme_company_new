<?php namespace Tests\Repositories;

use App\Models\SystemConfigurationAttributes;
use App\Repositories\SystemConfigurationAttributesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class SystemConfigurationAttributesRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var SystemConfigurationAttributesRepository
     */
    protected $systemConfigurationAttributesRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->systemConfigurationAttributesRepo = \App::make(SystemConfigurationAttributesRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_system_configuration_attributes()
    {
        $systemConfigurationAttributes = SystemConfigurationAttributes::factory()->make()->toArray();

        $createdSystemConfigurationAttributes = $this->systemConfigurationAttributesRepo->create($systemConfigurationAttributes);

        $createdSystemConfigurationAttributes = $createdSystemConfigurationAttributes->toArray();
        $this->assertArrayHasKey('id', $createdSystemConfigurationAttributes);
        $this->assertNotNull($createdSystemConfigurationAttributes['id'], 'Created SystemConfigurationAttributes must have id specified');
        $this->assertNotNull(SystemConfigurationAttributes::find($createdSystemConfigurationAttributes['id']), 'SystemConfigurationAttributes with given id must be in DB');
        $this->assertModelData($systemConfigurationAttributes, $createdSystemConfigurationAttributes);
    }

    /**
     * @test read
     */
    public function test_read_system_configuration_attributes()
    {
        $systemConfigurationAttributes = SystemConfigurationAttributes::factory()->create();

        $dbSystemConfigurationAttributes = $this->systemConfigurationAttributesRepo->find($systemConfigurationAttributes->id);

        $dbSystemConfigurationAttributes = $dbSystemConfigurationAttributes->toArray();
        $this->assertModelData($systemConfigurationAttributes->toArray(), $dbSystemConfigurationAttributes);
    }

    /**
     * @test update
     */
    public function test_update_system_configuration_attributes()
    {
        $systemConfigurationAttributes = SystemConfigurationAttributes::factory()->create();
        $fakeSystemConfigurationAttributes = SystemConfigurationAttributes::factory()->make()->toArray();

        $updatedSystemConfigurationAttributes = $this->systemConfigurationAttributesRepo->update($fakeSystemConfigurationAttributes, $systemConfigurationAttributes->id);

        $this->assertModelData($fakeSystemConfigurationAttributes, $updatedSystemConfigurationAttributes->toArray());
        $dbSystemConfigurationAttributes = $this->systemConfigurationAttributesRepo->find($systemConfigurationAttributes->id);
        $this->assertModelData($fakeSystemConfigurationAttributes, $dbSystemConfigurationAttributes->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_system_configuration_attributes()
    {
        $systemConfigurationAttributes = SystemConfigurationAttributes::factory()->create();

        $resp = $this->systemConfigurationAttributesRepo->delete($systemConfigurationAttributes->id);

        $this->assertTrue($resp);
        $this->assertNull(SystemConfigurationAttributes::find($systemConfigurationAttributes->id), 'SystemConfigurationAttributes should not exist in DB');
    }
}
