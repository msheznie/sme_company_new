<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Collection;

class  NavigationService
{
    protected Role $role;
    protected Collection $navigations;
    public $tenant_id;

    public function __construct(Role $role, $tenantId)
    {
        $this->role = $role;
        $this->tenant_id = $tenantId;
    }

    public function handle()
    {
        return $this->setNavs()->build();
    }

    public function setNavs()
    {
        $tenantID = $this->tenant_id;
        $this->navigations = collect($this->role->navigations->map(function ($nav) {
            return   [
                'id' => $nav->id,
                'path' => $nav->path,
                'title' => $nav->name,
                'icon' => $nav->icon,
                'type' => $nav->has_children ? 'sub' : 'link',
                'badgeType' => $nav->has_children ? 'primary' : '',
                'active' => false,
                'parent_id' => $nav->parent_id,
                'has_children' => $nav->has_children,
                'order_index' => $nav->order_index,
                'tenant_id' => $nav->tenant_id,
            ];
        }));

        if ($this->role->id != 1) {
            $this->navigations = $this->navigations->where('tenant_id', $tenantID);
        } else{
            //for admin users only GSUP-1310
            $this->navigations = $this->navigations->where('tenant_id', 1);
        }

        $this->navigations = $this->navigations->sortBy('id');

        return $this;
    }

    public function build()
    {
        return $this->firstLevel()->map(function ($nav) {
            if ($nav['has_children']) {
                $nav['children'] = $this->getChild($nav['id']);

                return $nav;
            } else {
                return $nav;
            }
        });
    }

    public function firstLevel()
    {
        return $this->navigations->filter(function ($nav) {
            return is_null($nav['parent_id']) || $nav['parent_id'] == '';
        })->reduce(function ($tree, $menu) {
            return $tree->push($menu);
        }, new Collection());
    }

    public function getChild($parent_id)
    {
        return $this->navigations->filter(function ($nav) use ($parent_id) {
            return $nav['parent_id'] === $parent_id;
        })->sortBy('order_index')->reduce(function ($tree, $menu) {
            if ($menu['parent_id']) {
                $menu['children'] = $this->getChild($menu['id']);

                return $tree->push($menu);
            } else {
                return $tree->push($menu);
            }
        }, new Collection());
    }

    public function bidTenderHandle()
    {
        return $this->setNavsBidTender()->build();
    }

    public function setNavsBidTender()
    {
        $tenantID = $this->tenant_id;
        $this->navigations = collect($this->role->navigations->map(function ($nav) {
            return   [
                'id' => $nav->id,
                'path' => $nav->path,
                'title' => $nav->name,
                'icon' => $nav->icon,
                'type' => $nav->has_children ? 'sub' : 'link',
                'badgeType' => $nav->has_children ? 'primary' : '',
                'active' => false,
                'parent_id' => $nav->parent_id,
                'has_children' => $nav->has_children,
                'order_index' => $nav->order_index,
                'tenant_id' => $nav->tenant_id,
            ];
        }))->whereIn('id',[10,11,14]);

        $this->navigations = $this->navigations->where('tenant_id', $tenantID);

        return $this;
    }
}
