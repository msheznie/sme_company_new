<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\{Navigation, Permission, Role, RoleHasPermissions};
use Illuminate\Support\Collection;
use League\CommonMark\Util\ArrayCollection;
use Spatie\Permission\Traits\HasPermissions;

class NavigationWithPermissionService
{
    use HasPermissions;
    protected Collection $navigations;
    protected Collection $role_navigations;
    protected $guarded = ['id'];
    public function __construct(int $role_id)
    {
        $this->role = Role::with(['navigations'])->where('id', $role_id)->first();

        $this->setRoleNavigation();
    }
    public function handle()
    {
        return $this->setNavs()->build();
    }

    private function setNavs()
    {
        $this->navigations = Navigation::with('permissions')->get(['id', 'name', 'icon', 'parent_id', 'has_children', 'order_index']);
        return $this;
    }
    private function build()
    {
        return $this->firstLevel()->map(function ($nav) {
            $nav->grant = $this->hasAccessToNavigation($nav->id);
            $nav->children = $this->getChild($nav->id);

            return $nav;
        });
    }
    private function firstLevel()
    {
        return $this->navigations->filter(function ($nav) {
            return is_null($nav->parent_id) || $nav->parent_id == '';
        })->reduce(function ($tree, $menu) {
            if ($menu->permissions) {
                $menu->permissions = $this->mapPermissions($menu->permissions);
            }

            return $tree->push($menu);
        }, new Collection());
    }

    private function mapPermissions($permissions)
    {
        return $permissions->reduce(function ($carry, $item) {
            $item->grant = $this->findUserPermission($item->name) || false;
            return $carry->push($item);
        }, new Collection());
    }
    private function getChild($parent_id)
    {
        return $this->navigations->filter(function ($nav) use ($parent_id) {
            return $nav->parent_id === $parent_id;
        })->reduce(function ($tree, $menu) {
            if ($menu->permissions) {
                $menu->permissions = $this->mapPermissions($menu->permissions);
            }
            $menu->grant = $this->hasAccessToNavigation($menu->id);

            if ($menu->parent_id) {
                $menu->children = $this->getChild($menu->id);

                return $tree->push($menu);
            } else {
                return $tree->push($menu);
            }
        }, new Collection());
    }

    public function hasAccessToNavigation($id)
    {
        return $this->role_navigations->contains('id', $id);
    }

    public function hasAccess($permission)
    {
        $permissionClass = $this->getPermissionClass();
        if (is_string($permission)) {
            $permission = $permissionClass->findByName($permission, $this->getDefaultGuardName());
        }
        if (is_int($permission)) {
            $permission = $permissionClass->findById($permission, $this->getDefaultGuardName());
        }
        $roleHasPermission = $this->roleHasPermission($permission->id, $this->role->id);
        return isset($roleHasPermission->permission_id) ? $roleHasPermission->permission_id : 0;
    }
    private function roleHasPermission($permissionID, $roleID)
    {
        return RoleHasPermissions::where('permission_id', $permissionID)
            ->where('role_id', $roleID)->first();
    }
    private function setRoleNavigation()
    {
        $this->role_navigations = new Collection($this->role->navigations);
    }
    public function findUserPermission($permission)
    {
        $userRoleID = $this->role->id;
        $permissionID = Permission::findByName($permission);
        $permission = RoleHasPermissions::with(['permission', 'role'])->select('permission_id')
            ->where('permission_id', $permissionID->id)
            ->where('role_id', $userRoleID)
            ->first();
        return (isset($permission) ? $permission : null);
    }
}
