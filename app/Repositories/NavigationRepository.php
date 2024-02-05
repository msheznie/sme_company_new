<?php

namespace App\Repositories;

use App\Models\Navigation;
use App\Repositories\BaseRepository;
use App\Services\NavigationWithPermissionService;

/**
 * Class NavigationRepository
 * @package App\Repositories
 * @version October 21, 2021, 8:41 am UTC
*/

class NavigationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'parent_id',
        'name',
        'path',
        'icon',
        'order_index',
        'has_children',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Navigation::class;
    }
    public function navigationTree($role_id, $tenantId)
    {
        $data = Navigation::select('id','name', 'icon', 'has_children', 'order_index', 'parent_id')
            ->whereNull('parent_id')
            ->withCount(['navigationHasRole as grant'=> function ($q1) use ($role_id, $tenantId){
                $q1->where('role_id', $role_id)
                   ->where('tenant_id', $tenantId);}])
            ->with(['permissions'=> function ($q) use ($role_id, $tenantId){
                $q->withCount(['RoleHasPermission as grant' => function ($q1) use ($role_id, $tenantId){
                    $q1->where('role_id',$role_id)
                       ->where('tenant_id',$tenantId);
                }]);
            }, 'children' => function($q2) use ($role_id, $tenantId){
                $q2->select('id','name', 'icon', 'has_children', 'order_index', 'parent_id')->withCount(['navigationHasRole as grant'=> function ($q1) use ($role_id, $tenantId){
                    $q1->where('role_id', $role_id)->where('tenant_id', $tenantId);}])->with(['permissions' => function($q3) use ($role_id, $tenantId){
                    $q3->withCount(['RoleHasPermission as grant' => function ($q4) use ($role_id, $tenantId){
                        $q4->where('role_id',$role_id)->where('tenant_id', $tenantId);
                    }]);
                }, 'children' => function($q5) use ($role_id, $tenantId){
                    $q5->select('id','name', 'icon', 'has_children', 'order_index', 'parent_id')
                        ->with(['permissions' => function($q6) use ($role_id, $tenantId){
                            $q6->withCount(['RoleHasPermission as grant' => function ($q7) use ($role_id, $tenantId){
                                $q7->where('role_id',$role_id)->where('tenant_id',$tenantId);
                            }]);
                        }]);
                }]);
            }])
            ->get();

        return $data;
    }

}
