<?php namespace Tests\Repositories;

use App\Models\ContractPaymentTermsAmd;
use App\Repositories\ContractPaymentTermsAmdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractPaymentTermsAmdRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractPaymentTermsAmdRepository
     */
    protected $contractPaymentTermsAmdRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractPaymentTermsAmdRepo = \App::make(ContractPaymentTermsAmdRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_payment_terms_amd()
    {
        $contractPaymentTermsAmd = ContractPaymentTermsAmd::factory()->make()->toArray();

        $createdContractPaymentTermsAmd = $this->contractPaymentTermsAmdRepo->create($contractPaymentTermsAmd);

        $createdContractPaymentTermsAmd = $createdContractPaymentTermsAmd->toArray();
        $this->assertArrayHasKey('id', $createdContractPaymentTermsAmd);
        $this->assertNotNull($createdContractPaymentTermsAmd['id'], 'Created ContractPaymentTermsAmd must have id specified');
        $this->assertNotNull(ContractPaymentTermsAmd::find($createdContractPaymentTermsAmd['id']), 'ContractPaymentTermsAmd with given id must be in DB');
        $this->assertModelData($contractPaymentTermsAmd, $createdContractPaymentTermsAmd);
    }

    /**
     * @test read
     */
    public function test_read_contract_payment_terms_amd()
    {
        $contractPaymentTermsAmd = ContractPaymentTermsAmd::factory()->create();

        $dbContractPaymentTermsAmd = $this->contractPaymentTermsAmdRepo->find($contractPaymentTermsAmd->id);

        $dbContractPaymentTermsAmd = $dbContractPaymentTermsAmd->toArray();
        $this->assertModelData($contractPaymentTermsAmd->toArray(), $dbContractPaymentTermsAmd);
    }

    /**
     * @test update
     */
    public function test_update_contract_payment_terms_amd()
    {
        $contractPaymentTermsAmd = ContractPaymentTermsAmd::factory()->create();
        $fakeContractPaymentTermsAmd = ContractPaymentTermsAmd::factory()->make()->toArray();

        $updatedContractPaymentTermsAmd = $this->contractPaymentTermsAmdRepo->update($fakeContractPaymentTermsAmd, $contractPaymentTermsAmd->id);

        $this->assertModelData($fakeContractPaymentTermsAmd, $updatedContractPaymentTermsAmd->toArray());
        $dbContractPaymentTermsAmd = $this->contractPaymentTermsAmdRepo->find($contractPaymentTermsAmd->id);
        $this->assertModelData($fakeContractPaymentTermsAmd, $dbContractPaymentTermsAmd->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_payment_terms_amd()
    {
        $contractPaymentTermsAmd = ContractPaymentTermsAmd::factory()->create();

        $resp = $this->contractPaymentTermsAmdRepo->delete($contractPaymentTermsAmd->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractPaymentTermsAmd::find($contractPaymentTermsAmd->id), 'ContractPaymentTermsAmd should not exist in DB');
    }
}
