<?php namespace Tests\Repositories;

use App\Models\TemplateMaster;
use App\Repositories\TemplateMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TemplateMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TemplateMasterRepository
     */
    protected $templateMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->templateMasterRepo = \App::make(TemplateMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_template_master()
    {
        $templateMaster = TemplateMaster::factory()->make()->toArray();

        $createdTemplateMaster = $this->templateMasterRepo->create($templateMaster);

        $createdTemplateMaster = $createdTemplateMaster->toArray();
        $this->assertArrayHasKey('id', $createdTemplateMaster);
        $this->assertNotNull($createdTemplateMaster['id'], 'Created TemplateMaster must have id specified');
        $this->assertNotNull(TemplateMaster::find($createdTemplateMaster['id']), 'TemplateMaster with given id must be in DB');
        $this->assertModelData($templateMaster, $createdTemplateMaster);
    }

    /**
     * @test read
     */
    public function test_read_template_master()
    {
        $templateMaster = TemplateMaster::factory()->create();

        $dbTemplateMaster = $this->templateMasterRepo->find($templateMaster->id);

        $dbTemplateMaster = $dbTemplateMaster->toArray();
        $this->assertModelData($templateMaster->toArray(), $dbTemplateMaster);
    }

    /**
     * @test update
     */
    public function test_update_template_master()
    {
        $templateMaster = TemplateMaster::factory()->create();
        $fakeTemplateMaster = TemplateMaster::factory()->make()->toArray();

        $updatedTemplateMaster = $this->templateMasterRepo->update($fakeTemplateMaster, $templateMaster->id);

        $this->assertModelData($fakeTemplateMaster, $updatedTemplateMaster->toArray());
        $dbTemplateMaster = $this->templateMasterRepo->find($templateMaster->id);
        $this->assertModelData($fakeTemplateMaster, $dbTemplateMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_template_master()
    {
        $templateMaster = TemplateMaster::factory()->create();

        $resp = $this->templateMasterRepo->delete($templateMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(TemplateMaster::find($templateMaster->id), 'TemplateMaster should not exist in DB');
    }
}
