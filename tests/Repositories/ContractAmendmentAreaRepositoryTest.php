<?php namespace Tests\Repositories;

use App\Models\ContractAmendmentArea;
use App\Repositories\ContractAmendmentAreaRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractAmendmentAreaRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractAmendmentAreaRepository
     */
    protected $contractAmendmentAreaRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractAmendmentAreaRepo = \App::make(ContractAmendmentAreaRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_amendment_area()
    {
        $contractAmendmentArea = ContractAmendmentArea::factory()->make()->toArray();

        $createdContractAmendmentArea = $this->contractAmendmentAreaRepo->create($contractAmendmentArea);

        $createdContractAmendmentArea = $createdContractAmendmentArea->toArray();
        $this->assertArrayHasKey('id', $createdContractAmendmentArea);
        $this->assertNotNull($createdContractAmendmentArea['id'], 'Created ContractAmendmentArea must have id specified');
        $this->assertNotNull(ContractAmendmentArea::find($createdContractAmendmentArea['id']), 'ContractAmendmentArea with given id must be in DB');
        $this->assertModelData($contractAmendmentArea, $createdContractAmendmentArea);
    }

    /**
     * @test read
     */
    public function test_read_contract_amendment_area()
    {
        $contractAmendmentArea = ContractAmendmentArea::factory()->create();

        $dbContractAmendmentArea = $this->contractAmendmentAreaRepo->find($contractAmendmentArea->id);

        $dbContractAmendmentArea = $dbContractAmendmentArea->toArray();
        $this->assertModelData($contractAmendmentArea->toArray(), $dbContractAmendmentArea);
    }

    /**
     * @test update
     */
    public function test_update_contract_amendment_area()
    {
        $contractAmendmentArea = ContractAmendmentArea::factory()->create();
        $fakeContractAmendmentArea = ContractAmendmentArea::factory()->make()->toArray();

        $updatedContractAmendmentArea = $this->contractAmendmentAreaRepo->update($fakeContractAmendmentArea, $contractAmendmentArea->id);

        $this->assertModelData($fakeContractAmendmentArea, $updatedContractAmendmentArea->toArray());
        $dbContractAmendmentArea = $this->contractAmendmentAreaRepo->find($contractAmendmentArea->id);
        $this->assertModelData($fakeContractAmendmentArea, $dbContractAmendmentArea->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_amendment_area()
    {
        $contractAmendmentArea = ContractAmendmentArea::factory()->create();

        $resp = $this->contractAmendmentAreaRepo->delete($contractAmendmentArea->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractAmendmentArea::find($contractAmendmentArea->id), 'ContractAmendmentArea should not exist in DB');
    }
}
