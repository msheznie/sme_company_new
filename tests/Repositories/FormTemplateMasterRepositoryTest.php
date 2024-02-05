<?php namespace Tests\Repositories;

use App\Models\FormTemplateMaster;
use App\Repositories\FormTemplateMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class FormTemplateMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var FormTemplateMasterRepository
     */
    protected $formTemplateMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->formTemplateMasterRepo = \App::make(FormTemplateMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_form_template_master()
    {
        $formTemplateMaster = FormTemplateMaster::factory()->make()->toArray();

        $createdFormTemplateMaster = $this->formTemplateMasterRepo->create($formTemplateMaster);

        $createdFormTemplateMaster = $createdFormTemplateMaster->toArray();
        $this->assertArrayHasKey('id', $createdFormTemplateMaster);
        $this->assertNotNull($createdFormTemplateMaster['id'], 'Created FormTemplateMaster must have id specified');
        $this->assertNotNull(FormTemplateMaster::find($createdFormTemplateMaster['id']), 'FormTemplateMaster with given id must be in DB');
        $this->assertModelData($formTemplateMaster, $createdFormTemplateMaster);
    }

    /**
     * @test read
     */
    public function test_read_form_template_master()
    {
        $formTemplateMaster = FormTemplateMaster::factory()->create();

        $dbFormTemplateMaster = $this->formTemplateMasterRepo->find($formTemplateMaster->id);

        $dbFormTemplateMaster = $dbFormTemplateMaster->toArray();
        $this->assertModelData($formTemplateMaster->toArray(), $dbFormTemplateMaster);
    }

    /**
     * @test update
     */
    public function test_update_form_template_master()
    {
        $formTemplateMaster = FormTemplateMaster::factory()->create();
        $fakeFormTemplateMaster = FormTemplateMaster::factory()->make()->toArray();

        $updatedFormTemplateMaster = $this->formTemplateMasterRepo->update($fakeFormTemplateMaster, $formTemplateMaster->id);

        $this->assertModelData($fakeFormTemplateMaster, $updatedFormTemplateMaster->toArray());
        $dbFormTemplateMaster = $this->formTemplateMasterRepo->find($formTemplateMaster->id);
        $this->assertModelData($fakeFormTemplateMaster, $dbFormTemplateMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_form_template_master()
    {
        $formTemplateMaster = FormTemplateMaster::factory()->create();

        $resp = $this->formTemplateMasterRepo->delete($formTemplateMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(FormTemplateMaster::find($formTemplateMaster->id), 'FormTemplateMaster should not exist in DB');
    }
}
