<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CMContractReminderScenario;

class CMContractReminderScenarioApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_c_m_contract_reminder_scenario()
    {
        $cMContractReminderScenario = CMContractReminderScenario::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/c_m_contract_reminder_scenarios', $cMContractReminderScenario
        );

        $this->assertApiResponse($cMContractReminderScenario);
    }

    /**
     * @test
     */
    public function test_read_c_m_contract_reminder_scenario()
    {
        $cMContractReminderScenario = CMContractReminderScenario::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_reminder_scenarios/'.$cMContractReminderScenario->id
        );

        $this->assertApiResponse($cMContractReminderScenario->toArray());
    }

    /**
     * @test
     */
    public function test_update_c_m_contract_reminder_scenario()
    {
        $cMContractReminderScenario = CMContractReminderScenario::factory()->create();
        $editedCMContractReminderScenario = CMContractReminderScenario::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/c_m_contract_reminder_scenarios/'.$cMContractReminderScenario->id,
            $editedCMContractReminderScenario
        );

        $this->assertApiResponse($editedCMContractReminderScenario);
    }

    /**
     * @test
     */
    public function test_delete_c_m_contract_reminder_scenario()
    {
        $cMContractReminderScenario = CMContractReminderScenario::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/c_m_contract_reminder_scenarios/'.$cMContractReminderScenario->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_reminder_scenarios/'.$cMContractReminderScenario->id
        );

        $this->response->assertStatus(404);
    }
}
