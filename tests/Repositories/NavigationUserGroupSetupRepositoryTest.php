<?php namespace Tests\Repositories;

use App\Models\NavigationUserGroupSetup;
use App\Repositories\NavigationUserGroupSetupRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class NavigationUserGroupSetupRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var NavigationUserGroupSetupRepository
     */
    protected $navigationUserGroupSetupRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->navigationUserGroupSetupRepo = \App::make(NavigationUserGroupSetupRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_navigation_user_group_setup()
    {
        $navigationUserGroupSetup = NavigationUserGroupSetup::factory()->make()->toArray();

        $createdNavigationUserGroupSetup = $this->navigationUserGroupSetupRepo->create($navigationUserGroupSetup);

        $createdNavigationUserGroupSetup = $createdNavigationUserGroupSetup->toArray();
        $this->assertArrayHasKey('id', $createdNavigationUserGroupSetup);
        $this->assertNotNull($createdNavigationUserGroupSetup['id'], 'Created NavigationUserGroupSetup must have id specified');
        $this->assertNotNull(NavigationUserGroupSetup::find($createdNavigationUserGroupSetup['id']), 'NavigationUserGroupSetup with given id must be in DB');
        $this->assertModelData($navigationUserGroupSetup, $createdNavigationUserGroupSetup);
    }

    /**
     * @test read
     */
    public function test_read_navigation_user_group_setup()
    {
        $navigationUserGroupSetup = NavigationUserGroupSetup::factory()->create();

        $dbNavigationUserGroupSetup = $this->navigationUserGroupSetupRepo->find($navigationUserGroupSetup->id);

        $dbNavigationUserGroupSetup = $dbNavigationUserGroupSetup->toArray();
        $this->assertModelData($navigationUserGroupSetup->toArray(), $dbNavigationUserGroupSetup);
    }

    /**
     * @test update
     */
    public function test_update_navigation_user_group_setup()
    {
        $navigationUserGroupSetup = NavigationUserGroupSetup::factory()->create();
        $fakeNavigationUserGroupSetup = NavigationUserGroupSetup::factory()->make()->toArray();

        $updatedNavigationUserGroupSetup = $this->navigationUserGroupSetupRepo->update($fakeNavigationUserGroupSetup, $navigationUserGroupSetup->id);

        $this->assertModelData($fakeNavigationUserGroupSetup, $updatedNavigationUserGroupSetup->toArray());
        $dbNavigationUserGroupSetup = $this->navigationUserGroupSetupRepo->find($navigationUserGroupSetup->id);
        $this->assertModelData($fakeNavigationUserGroupSetup, $dbNavigationUserGroupSetup->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_navigation_user_group_setup()
    {
        $navigationUserGroupSetup = NavigationUserGroupSetup::factory()->create();

        $resp = $this->navigationUserGroupSetupRepo->delete($navigationUserGroupSetup->id);

        $this->assertTrue($resp);
        $this->assertNull(NavigationUserGroupSetup::find($navigationUserGroupSetup->id), 'NavigationUserGroupSetup should not exist in DB');
    }
}
