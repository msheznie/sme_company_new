<?php namespace Tests\Repositories;

use App\Models\CodeConfigurations;
use App\Repositories\CodeConfigurationsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CodeConfigurationsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CodeConfigurationsRepository
     */
    protected $codeConfigurationsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->codeConfigurationsRepo = \App::make(CodeConfigurationsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_code_configurations()
    {
        $codeConfigurations = CodeConfigurations::factory()->make()->toArray();

        $createdCodeConfigurations = $this->codeConfigurationsRepo->create($codeConfigurations);

        $createdCodeConfigurations = $createdCodeConfigurations->toArray();
        $this->assertArrayHasKey('id', $createdCodeConfigurations);
        $this->assertNotNull($createdCodeConfigurations['id'], 'Created CodeConfigurations must have id specified');
        $this->assertNotNull(CodeConfigurations::find($createdCodeConfigurations['id']), 'CodeConfigurations with given id must be in DB');
        $this->assertModelData($codeConfigurations, $createdCodeConfigurations);
    }

    /**
     * @test read
     */
    public function test_read_code_configurations()
    {
        $codeConfigurations = CodeConfigurations::factory()->create();

        $dbCodeConfigurations = $this->codeConfigurationsRepo->find($codeConfigurations->id);

        $dbCodeConfigurations = $dbCodeConfigurations->toArray();
        $this->assertModelData($codeConfigurations->toArray(), $dbCodeConfigurations);
    }

    /**
     * @test update
     */
    public function test_update_code_configurations()
    {
        $codeConfigurations = CodeConfigurations::factory()->create();
        $fakeCodeConfigurations = CodeConfigurations::factory()->make()->toArray();

        $updatedCodeConfigurations = $this->codeConfigurationsRepo->update($fakeCodeConfigurations, $codeConfigurations->id);

        $this->assertModelData($fakeCodeConfigurations, $updatedCodeConfigurations->toArray());
        $dbCodeConfigurations = $this->codeConfigurationsRepo->find($codeConfigurations->id);
        $this->assertModelData($fakeCodeConfigurations, $dbCodeConfigurations->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_code_configurations()
    {
        $codeConfigurations = CodeConfigurations::factory()->create();

        $resp = $this->codeConfigurationsRepo->delete($codeConfigurations->id);

        $this->assertTrue($resp);
        $this->assertNull(CodeConfigurations::find($codeConfigurations->id), 'CodeConfigurations should not exist in DB');
    }
}
