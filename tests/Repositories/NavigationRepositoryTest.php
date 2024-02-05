<?php namespace Tests\Repositories;

use App\Models\Navigation;
use App\Repositories\NavigationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class NavigationRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var NavigationRepository
     */
    protected $navigationRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->navigationRepo = \App::make(NavigationRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_navigation()
    {
        $navigation = Navigation::factory()->make()->toArray();

        $createdNavigation = $this->navigationRepo->create($navigation);

        $createdNavigation = $createdNavigation->toArray();
        $this->assertArrayHasKey('id', $createdNavigation);
        $this->assertNotNull($createdNavigation['id'], 'Created Navigation must have id specified');
        $this->assertNotNull(Navigation::find($createdNavigation['id']), 'Navigation with given id must be in DB');
        $this->assertModelData($navigation, $createdNavigation);
    }

    /**
     * @test read
     */
    public function test_read_navigation()
    {
        $navigation = Navigation::factory()->create();

        $dbNavigation = $this->navigationRepo->find($navigation->id);

        $dbNavigation = $dbNavigation->toArray();
        $this->assertModelData($navigation->toArray(), $dbNavigation);
    }

    /**
     * @test update
     */
    public function test_update_navigation()
    {
        $navigation = Navigation::factory()->create();
        $fakeNavigation = Navigation::factory()->make()->toArray();

        $updatedNavigation = $this->navigationRepo->update($fakeNavigation, $navigation->id);

        $this->assertModelData($fakeNavigation, $updatedNavigation->toArray());
        $dbNavigation = $this->navigationRepo->find($navigation->id);
        $this->assertModelData($fakeNavigation, $dbNavigation->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_navigation()
    {
        $navigation = Navigation::factory()->create();

        $resp = $this->navigationRepo->delete($navigation->id);

        $this->assertTrue($resp);
        $this->assertNull(Navigation::find($navigation->id), 'Navigation should not exist in DB');
    }
}
