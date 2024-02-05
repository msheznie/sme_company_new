<?php namespace Tests\Repositories;

use App\Models\NavigationRole;
use App\Repositories\NavigationRoleRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class NavigationRoleRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var NavigationRoleRepository
     */
    protected $navigationRoleRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->navigationRoleRepo = \App::make(NavigationRoleRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_navigation_role()
    {
        $navigationRole = NavigationRole::factory()->make()->toArray();

        $createdNavigationRole = $this->navigationRoleRepo->create($navigationRole);

        $createdNavigationRole = $createdNavigationRole->toArray();
        $this->assertArrayHasKey('id', $createdNavigationRole);
        $this->assertNotNull($createdNavigationRole['id'], 'Created NavigationRole must have id specified');
        $this->assertNotNull(NavigationRole::find($createdNavigationRole['id']), 'NavigationRole with given id must be in DB');
        $this->assertModelData($navigationRole, $createdNavigationRole);
    }

    /**
     * @test read
     */
    public function test_read_navigation_role()
    {
        $navigationRole = NavigationRole::factory()->create();

        $dbNavigationRole = $this->navigationRoleRepo->find($navigationRole->id);

        $dbNavigationRole = $dbNavigationRole->toArray();
        $this->assertModelData($navigationRole->toArray(), $dbNavigationRole);
    }

    /**
     * @test update
     */
    public function test_update_navigation_role()
    {
        $navigationRole = NavigationRole::factory()->create();
        $fakeNavigationRole = NavigationRole::factory()->make()->toArray();

        $updatedNavigationRole = $this->navigationRoleRepo->update($fakeNavigationRole, $navigationRole->id);

        $this->assertModelData($fakeNavigationRole, $updatedNavigationRole->toArray());
        $dbNavigationRole = $this->navigationRoleRepo->find($navigationRole->id);
        $this->assertModelData($fakeNavigationRole, $dbNavigationRole->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_navigation_role()
    {
        $navigationRole = NavigationRole::factory()->create();

        $resp = $this->navigationRoleRepo->delete($navigationRole->id);

        $this->assertTrue($resp);
        $this->assertNull(NavigationRole::find($navigationRole->id), 'NavigationRole should not exist in DB');
    }
}
