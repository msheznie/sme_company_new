<?php namespace Tests\Repositories;

use App\Models\CMContractTypeSections;
use App\Repositories\CMContractTypeSectionsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMContractTypeSectionsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMContractTypeSectionsRepository
     */
    protected $cMContractTypeSectionsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMContractTypeSectionsRepo = \App::make(CMContractTypeSectionsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_contract_type_sections()
    {
        $cMContractTypeSections = CMContractTypeSections::factory()->make()->toArray();

        $createdCMContractTypeSections = $this->cMContractTypeSectionsRepo->create($cMContractTypeSections);

        $createdCMContractTypeSections = $createdCMContractTypeSections->toArray();
        $this->assertArrayHasKey('id', $createdCMContractTypeSections);
        $this->assertNotNull($createdCMContractTypeSections['id'], 'Created CMContractTypeSections must have id specified');
        $this->assertNotNull(CMContractTypeSections::find($createdCMContractTypeSections['id']), 'CMContractTypeSections with given id must be in DB');
        $this->assertModelData($cMContractTypeSections, $createdCMContractTypeSections);
    }

    /**
     * @test read
     */
    public function test_read_c_m_contract_type_sections()
    {
        $cMContractTypeSections = CMContractTypeSections::factory()->create();

        $dbCMContractTypeSections = $this->cMContractTypeSectionsRepo->find($cMContractTypeSections->id);

        $dbCMContractTypeSections = $dbCMContractTypeSections->toArray();
        $this->assertModelData($cMContractTypeSections->toArray(), $dbCMContractTypeSections);
    }

    /**
     * @test update
     */
    public function test_update_c_m_contract_type_sections()
    {
        $cMContractTypeSections = CMContractTypeSections::factory()->create();
        $fakeCMContractTypeSections = CMContractTypeSections::factory()->make()->toArray();

        $updatedCMContractTypeSections = $this->cMContractTypeSectionsRepo->update($fakeCMContractTypeSections, $cMContractTypeSections->id);

        $this->assertModelData($fakeCMContractTypeSections, $updatedCMContractTypeSections->toArray());
        $dbCMContractTypeSections = $this->cMContractTypeSectionsRepo->find($cMContractTypeSections->id);
        $this->assertModelData($fakeCMContractTypeSections, $dbCMContractTypeSections->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_contract_type_sections()
    {
        $cMContractTypeSections = CMContractTypeSections::factory()->create();

        $resp = $this->cMContractTypeSectionsRepo->delete($cMContractTypeSections->id);

        $this->assertTrue($resp);
        $this->assertNull(CMContractTypeSections::find($cMContractTypeSections->id), 'CMContractTypeSections should not exist in DB');
    }
}
