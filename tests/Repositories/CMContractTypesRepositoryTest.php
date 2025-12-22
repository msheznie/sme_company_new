<?php namespace Tests\Repositories;

use App\Models\CMContractTypes;
use App\Repositories\CMContractTypesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMContractTypesRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMContractTypesRepository
     */
    protected $cMContractTypesRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMContractTypesRepo = \App::make(CMContractTypesRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_contract_types()
    {
        $cMContractTypes = CMContractTypes::factory()->make()->toArray();

        $createdCMContractTypes = $this->cMContractTypesRepo->create($cMContractTypes);

        $createdCMContractTypes = $createdCMContractTypes->toArray();
        $this->assertArrayHasKey('id', $createdCMContractTypes);
        $this->assertNotNull($createdCMContractTypes['id'], 'Created CMContractTypes must have id specified');
        $this->assertNotNull(CMContractTypes::find($createdCMContractTypes['id']), 'CMContractTypes with given id must be in DB');
        $this->assertModelData($cMContractTypes, $createdCMContractTypes);
    }

    /**
     * @test read
     */
    public function test_read_c_m_contract_types()
    {
        $cMContractTypes = CMContractTypes::factory()->create();

        $dbCMContractTypes = $this->cMContractTypesRepo->find($cMContractTypes->id);

        $dbCMContractTypes = $dbCMContractTypes->toArray();
        $this->assertModelData($cMContractTypes->toArray(), $dbCMContractTypes);
    }

    /**
     * @test update
     */
    public function test_update_c_m_contract_types()
    {
        $cMContractTypes = CMContractTypes::factory()->create();
        $fakeCMContractTypes = CMContractTypes::factory()->make()->toArray();

        $updatedCMContractTypes = $this->cMContractTypesRepo->update($fakeCMContractTypes, $cMContractTypes->id);

        $this->assertModelData($fakeCMContractTypes, $updatedCMContractTypes->toArray());
        $dbCMContractTypes = $this->cMContractTypesRepo->find($cMContractTypes->id);
        $this->assertModelData($fakeCMContractTypes, $dbCMContractTypes->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_contract_types()
    {
        $cMContractTypes = CMContractTypes::factory()->create();

        $resp = $this->cMContractTypesRepo->delete($cMContractTypes->id);

        $this->assertTrue($resp);
        $this->assertNull(CMContractTypes::find($cMContractTypes->id), 'CMContractTypes should not exist in DB');
    }
}
