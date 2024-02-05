<?php namespace Tests\Repositories;

use App\Models\RoleHasPermissions;
use App\Repositories\RoleHasPermissionsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class RoleHasPermissionsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var RoleHasPermissionsRepository
     */
    protected $roleHasPermissionsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->roleHasPermissionsRepo = \App::make(RoleHasPermissionsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_role_has_permissions()
    {
        $roleHasPermissions = RoleHasPermissions::factory()->make()->toArray();

        $createdRoleHasPermissions = $this->roleHasPermissionsRepo->create($roleHasPermissions);

        $createdRoleHasPermissions = $createdRoleHasPermissions->toArray();
        $this->assertArrayHasKey('id', $createdRoleHasPermissions);
        $this->assertNotNull($createdRoleHasPermissions['id'], 'Created RoleHasPermissions must have id specified');
        $this->assertNotNull(RoleHasPermissions::find($createdRoleHasPermissions['id']), 'RoleHasPermissions with given id must be in DB');
        $this->assertModelData($roleHasPermissions, $createdRoleHasPermissions);
    }

    /**
     * @test read
     */
    public function test_read_role_has_permissions()
    {
        $roleHasPermissions = RoleHasPermissions::factory()->create();

        $dbRoleHasPermissions = $this->roleHasPermissionsRepo->find($roleHasPermissions->id);

        $dbRoleHasPermissions = $dbRoleHasPermissions->toArray();
        $this->assertModelData($roleHasPermissions->toArray(), $dbRoleHasPermissions);
    }

    /**
     * @test update
     */
    public function test_update_role_has_permissions()
    {
        $roleHasPermissions = RoleHasPermissions::factory()->create();
        $fakeRoleHasPermissions = RoleHasPermissions::factory()->make()->toArray();

        $updatedRoleHasPermissions = $this->roleHasPermissionsRepo->update($fakeRoleHasPermissions, $roleHasPermissions->id);

        $this->assertModelData($fakeRoleHasPermissions, $updatedRoleHasPermissions->toArray());
        $dbRoleHasPermissions = $this->roleHasPermissionsRepo->find($roleHasPermissions->id);
        $this->assertModelData($fakeRoleHasPermissions, $dbRoleHasPermissions->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_role_has_permissions()
    {
        $roleHasPermissions = RoleHasPermissions::factory()->create();

        $resp = $this->roleHasPermissionsRepo->delete($roleHasPermissions->id);

        $this->assertTrue($resp);
        $this->assertNull(RoleHasPermissions::find($roleHasPermissions->id), 'RoleHasPermissions should not exist in DB');
    }
}
