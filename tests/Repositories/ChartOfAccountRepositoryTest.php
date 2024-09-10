<?php namespace Tests\Repositories;

use App\Models\ChartOfAccount;
use App\Repositories\ChartOfAccountRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ChartOfAccountRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ChartOfAccountRepository
     */
    protected $chartOfAccountRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->chartOfAccountRepo = \App::make(ChartOfAccountRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_chart_of_account()
    {
        $chartOfAccount = ChartOfAccount::factory()->make()->toArray();

        $createdChartOfAccount = $this->chartOfAccountRepo->create($chartOfAccount);

        $createdChartOfAccount = $createdChartOfAccount->toArray();
        $this->assertArrayHasKey('id', $createdChartOfAccount);
        $this->assertNotNull($createdChartOfAccount['id'], 'Created ChartOfAccount must have id specified');
        $this->assertNotNull(ChartOfAccount::find($createdChartOfAccount['id']), 'ChartOfAccount with given id must be in DB');
        $this->assertModelData($chartOfAccount, $createdChartOfAccount);
    }

    /**
     * @test read
     */
    public function test_read_chart_of_account()
    {
        $chartOfAccount = ChartOfAccount::factory()->create();

        $dbChartOfAccount = $this->chartOfAccountRepo->find($chartOfAccount->id);

        $dbChartOfAccount = $dbChartOfAccount->toArray();
        $this->assertModelData($chartOfAccount->toArray(), $dbChartOfAccount);
    }

    /**
     * @test update
     */
    public function test_update_chart_of_account()
    {
        $chartOfAccount = ChartOfAccount::factory()->create();
        $fakeChartOfAccount = ChartOfAccount::factory()->make()->toArray();

        $updatedChartOfAccount = $this->chartOfAccountRepo->update($fakeChartOfAccount, $chartOfAccount->id);

        $this->assertModelData($fakeChartOfAccount, $updatedChartOfAccount->toArray());
        $dbChartOfAccount = $this->chartOfAccountRepo->find($chartOfAccount->id);
        $this->assertModelData($fakeChartOfAccount, $dbChartOfAccount->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_chart_of_account()
    {
        $chartOfAccount = ChartOfAccount::factory()->create();

        $resp = $this->chartOfAccountRepo->delete($chartOfAccount->id);

        $this->assertTrue($resp);
        $this->assertNull(ChartOfAccount::find($chartOfAccount->id), 'ChartOfAccount should not exist in DB');
    }
}
