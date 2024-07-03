<?php namespace Tests\Repositories;

use App\Models\ContractPaymentTerms;
use App\Repositories\ContractPaymentTermsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractPaymentTermsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractPaymentTermsRepository
     */
    protected $contractPaymentTermsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractPaymentTermsRepo = \App::make(ContractPaymentTermsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_payment_terms()
    {
        $contractPaymentTerms = ContractPaymentTerms::factory()->make()->toArray();

        $createdContractPaymentTerms = $this->contractPaymentTermsRepo->create($contractPaymentTerms);

        $createdContractPaymentTerms = $createdContractPaymentTerms->toArray();
        $this->assertArrayHasKey('id', $createdContractPaymentTerms);
        $this->assertNotNull($createdContractPaymentTerms['id'], 'Created ContractPaymentTerms must have id specified');
        $this->assertNotNull(ContractPaymentTerms::find($createdContractPaymentTerms['id']), 'ContractPaymentTerms with given id must be in DB');
        $this->assertModelData($contractPaymentTerms, $createdContractPaymentTerms);
    }

    /**
     * @test read
     */
    public function test_read_contract_payment_terms()
    {
        $contractPaymentTerms = ContractPaymentTerms::factory()->create();

        $dbContractPaymentTerms = $this->contractPaymentTermsRepo->find($contractPaymentTerms->id);

        $dbContractPaymentTerms = $dbContractPaymentTerms->toArray();
        $this->assertModelData($contractPaymentTerms->toArray(), $dbContractPaymentTerms);
    }

    /**
     * @test update
     */
    public function test_update_contract_payment_terms()
    {
        $contractPaymentTerms = ContractPaymentTerms::factory()->create();
        $fakeContractPaymentTerms = ContractPaymentTerms::factory()->make()->toArray();

        $updatedContractPaymentTerms = $this->contractPaymentTermsRepo->update($fakeContractPaymentTerms, $contractPaymentTerms->id);

        $this->assertModelData($fakeContractPaymentTerms, $updatedContractPaymentTerms->toArray());
        $dbContractPaymentTerms = $this->contractPaymentTermsRepo->find($contractPaymentTerms->id);
        $this->assertModelData($fakeContractPaymentTerms, $dbContractPaymentTerms->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_payment_terms()
    {
        $contractPaymentTerms = ContractPaymentTerms::factory()->create();

        $resp = $this->contractPaymentTermsRepo->delete($contractPaymentTerms->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractPaymentTerms::find($contractPaymentTerms->id), 'ContractPaymentTerms should not exist in DB');
    }
}
