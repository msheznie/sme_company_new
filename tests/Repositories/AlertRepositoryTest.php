<?php namespace Tests\Repositories;

use App\Models\Alert;
use App\Repositories\AlertRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class AlertRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var AlertRepository
     */
    protected $alertRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->alertRepo = \App::make(AlertRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_alert()
    {
        $alert = Alert::factory()->make()->toArray();

        $createdAlert = $this->alertRepo->create($alert);

        $createdAlert = $createdAlert->toArray();
        $this->assertArrayHasKey('id', $createdAlert);
        $this->assertNotNull($createdAlert['id'], 'Created Alert must have id specified');
        $this->assertNotNull(Alert::find($createdAlert['id']), 'Alert with given id must be in DB');
        $this->assertModelData($alert, $createdAlert);
    }

    /**
     * @test read
     */
    public function test_read_alert()
    {
        $alert = Alert::factory()->create();

        $dbAlert = $this->alertRepo->find($alert->id);

        $dbAlert = $dbAlert->toArray();
        $this->assertModelData($alert->toArray(), $dbAlert);
    }

    /**
     * @test update
     */
    public function test_update_alert()
    {
        $alert = Alert::factory()->create();
        $fakeAlert = Alert::factory()->make()->toArray();

        $updatedAlert = $this->alertRepo->update($fakeAlert, $alert->id);

        $this->assertModelData($fakeAlert, $updatedAlert->toArray());
        $dbAlert = $this->alertRepo->find($alert->id);
        $this->assertModelData($fakeAlert, $dbAlert->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_alert()
    {
        $alert = Alert::factory()->create();

        $resp = $this->alertRepo->delete($alert->id);

        $this->assertTrue($resp);
        $this->assertNull(Alert::find($alert->id), 'Alert should not exist in DB');
    }
}
