<?php namespace Tests\Repositories;

use App\Models\FinanceDocuments;
use App\Repositories\FinanceDocumentsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class FinanceDocumentsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var FinanceDocumentsRepository
     */
    protected $financeDocumentsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->financeDocumentsRepo = \App::make(FinanceDocumentsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_finance_documents()
    {
        $financeDocuments = FinanceDocuments::factory()->make()->toArray();

        $createdFinanceDocuments = $this->financeDocumentsRepo->create($financeDocuments);

        $createdFinanceDocuments = $createdFinanceDocuments->toArray();
        $this->assertArrayHasKey('id', $createdFinanceDocuments);
        $this->assertNotNull($createdFinanceDocuments['id'], 'Created FinanceDocuments must have id specified');
        $this->assertNotNull(FinanceDocuments::find($createdFinanceDocuments['id']), 'FinanceDocuments with given id must be in DB');
        $this->assertModelData($financeDocuments, $createdFinanceDocuments);
    }

    /**
     * @test read
     */
    public function test_read_finance_documents()
    {
        $financeDocuments = FinanceDocuments::factory()->create();

        $dbFinanceDocuments = $this->financeDocumentsRepo->find($financeDocuments->id);

        $dbFinanceDocuments = $dbFinanceDocuments->toArray();
        $this->assertModelData($financeDocuments->toArray(), $dbFinanceDocuments);
    }

    /**
     * @test update
     */
    public function test_update_finance_documents()
    {
        $financeDocuments = FinanceDocuments::factory()->create();
        $fakeFinanceDocuments = FinanceDocuments::factory()->make()->toArray();

        $updatedFinanceDocuments = $this->financeDocumentsRepo->update($fakeFinanceDocuments, $financeDocuments->id);

        $this->assertModelData($fakeFinanceDocuments, $updatedFinanceDocuments->toArray());
        $dbFinanceDocuments = $this->financeDocumentsRepo->find($financeDocuments->id);
        $this->assertModelData($fakeFinanceDocuments, $dbFinanceDocuments->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_finance_documents()
    {
        $financeDocuments = FinanceDocuments::factory()->create();

        $resp = $this->financeDocumentsRepo->delete($financeDocuments->id);

        $this->assertTrue($resp);
        $this->assertNull(FinanceDocuments::find($financeDocuments->id), 'FinanceDocuments should not exist in DB');
    }
}
