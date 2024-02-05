<?php namespace Tests\Repositories;

use App\Models\PermissionsModel;
use App\Repositories\PermissionsModelRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PermissionsModelRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var PermissionsModelRepository
     */
    protected $permissionsModelRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->permissionsModelRepo = \App::make(PermissionsModelRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_permissions_model()
    {
        $permissionsModel = PermissionsModel::factory()->make()->toArray();

        $createdPermissionsModel = $this->permissionsModelRepo->create($permissionsModel);

        $createdPermissionsModel = $createdPermissionsModel->toArray();
        $this->assertArrayHasKey('id', $createdPermissionsModel);
        $this->assertNotNull($createdPermissionsModel['id'], 'Created PermissionsModel must have id specified');
        $this->assertNotNull(PermissionsModel::find($createdPermissionsModel['id']), 'PermissionsModel with given id must be in DB');
        $this->assertModelData($permissionsModel, $createdPermissionsModel);
    }

    /**
     * @test read
     */
    public function test_read_permissions_model()
    {
        $permissionsModel = PermissionsModel::factory()->create();

        $dbPermissionsModel = $this->permissionsModelRepo->find($permissionsModel->id);

        $dbPermissionsModel = $dbPermissionsModel->toArray();
        $this->assertModelData($permissionsModel->toArray(), $dbPermissionsModel);
    }

    /**
     * @test update
     */
    public function test_update_permissions_model()
    {
        $permissionsModel = PermissionsModel::factory()->create();
        $fakePermissionsModel = PermissionsModel::factory()->make()->toArray();

        $updatedPermissionsModel = $this->permissionsModelRepo->update($fakePermissionsModel, $permissionsModel->id);

        $this->assertModelData($fakePermissionsModel, $updatedPermissionsModel->toArray());
        $dbPermissionsModel = $this->permissionsModelRepo->find($permissionsModel->id);
        $this->assertModelData($fakePermissionsModel, $dbPermissionsModel->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_permissions_model()
    {
        $permissionsModel = PermissionsModel::factory()->create();

        $resp = $this->permissionsModelRepo->delete($permissionsModel->id);

        $this->assertTrue($resp);
        $this->assertNull(PermissionsModel::find($permissionsModel->id), 'PermissionsModel should not exist in DB');
    }
}
