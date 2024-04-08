<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractSettingMaster;

class ContractSettingMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_setting_master()
    {
        $contractSettingMaster = ContractSettingMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_setting_masters', $contractSettingMaster
        );

        $this->assertApiResponse($contractSettingMaster);
    }

    /**
     * @test
     */
    public function test_read_contract_setting_master()
    {
        $contractSettingMaster = ContractSettingMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_setting_masters/'.$contractSettingMaster->id
        );

        $this->assertApiResponse($contractSettingMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_setting_master()
    {
        $contractSettingMaster = ContractSettingMaster::factory()->create();
        $editedContractSettingMaster = ContractSettingMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_setting_masters/'.$contractSettingMaster->id,
            $editedContractSettingMaster
        );

        $this->assertApiResponse($editedContractSettingMaster);
    }

    /**
     * @test
     */
    public function test_delete_contract_setting_master()
    {
        $contractSettingMaster = ContractSettingMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_setting_masters/'.$contractSettingMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_setting_masters/'.$contractSettingMaster->id
        );

        $this->response->assertStatus(404);
    }
}
