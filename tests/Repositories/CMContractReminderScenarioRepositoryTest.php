<?php namespace Tests\Repositories;

use App\Models\CMContractReminderScenario;
use App\Repositories\CMContractReminderScenarioRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMContractReminderScenarioRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMContractReminderScenarioRepository
     */
    protected $cMContractReminderScenarioRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMContractReminderScenarioRepo = \App::make(CMContractReminderScenarioRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_contract_reminder_scenario()
    {
        $cMContractReminderScenario = CMContractReminderScenario::factory()->make()->toArray();

        $createdCMContractReminderScenario = $this->cMContractReminderScenarioRepo->create($cMContractReminderScenario);

        $createdCMContractReminderScenario = $createdCMContractReminderScenario->toArray();
        $this->assertArrayHasKey('id', $createdCMContractReminderScenario);
        $this->assertNotNull($createdCMContractReminderScenario['id'], 'Created CMContractReminderScenario must have id specified');
        $this->assertNotNull(CMContractReminderScenario::find($createdCMContractReminderScenario['id']), 'CMContractReminderScenario with given id must be in DB');
        $this->assertModelData($cMContractReminderScenario, $createdCMContractReminderScenario);
    }

    /**
     * @test read
     */
    public function test_read_c_m_contract_reminder_scenario()
    {
        $cMContractReminderScenario = CMContractReminderScenario::factory()->create();

        $dbCMContractReminderScenario = $this->cMContractReminderScenarioRepo->find($cMContractReminderScenario->id);

        $dbCMContractReminderScenario = $dbCMContractReminderScenario->toArray();
        $this->assertModelData($cMContractReminderScenario->toArray(), $dbCMContractReminderScenario);
    }

    /**
     * @test update
     */
    public function test_update_c_m_contract_reminder_scenario()
    {
        $cMContractReminderScenario = CMContractReminderScenario::factory()->create();
        $fakeCMContractReminderScenario = CMContractReminderScenario::factory()->make()->toArray();

        $updatedCMContractReminderScenario = $this->cMContractReminderScenarioRepo->update($fakeCMContractReminderScenario, $cMContractReminderScenario->id);

        $this->assertModelData($fakeCMContractReminderScenario, $updatedCMContractReminderScenario->toArray());
        $dbCMContractReminderScenario = $this->cMContractReminderScenarioRepo->find($cMContractReminderScenario->id);
        $this->assertModelData($fakeCMContractReminderScenario, $dbCMContractReminderScenario->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_contract_reminder_scenario()
    {
        $cMContractReminderScenario = CMContractReminderScenario::factory()->create();

        $resp = $this->cMContractReminderScenarioRepo->delete($cMContractReminderScenario->id);

        $this->assertTrue($resp);
        $this->assertNull(CMContractReminderScenario::find($cMContractReminderScenario->id), 'CMContractReminderScenario should not exist in DB');
    }
}
