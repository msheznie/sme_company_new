<?php

namespace App\Http\Controllers\API;

use App\Helper\Helper;
use App\Http\Requests\API\CreateNavigationAPIRequest;
use App\Http\Requests\API\UpdateNavigationAPIRequest;
use App\Models\Navigation;
use App\Models\NavigationRole;
use App\Repositories\NavigationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\NavigationResource;
use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Response;
use League\Flysystem\Exception;
use App\Services\Shared\TenantService;
use App\Services\NavigationService;
/**
 * Class NavigationController
 * @package App\Http\Controllers\API
 */

class NavigationAPIController extends AppBaseController
{
    /** @var  NavigationRepository */
    private $navigationRepository;

    public function __construct(NavigationRepository $navigationRepo)
    {
        $this->navigationRepository = $navigationRepo;
    }

    /**
     * Display a listing of the Navigation.
     * GET|HEAD /navigations
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        Helper::can('dashboard.index');

        $navigations = $this->navigationRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(NavigationResource::collection($navigations), 'Navigations retrieved successfully');
    }

    /**
     * Store a newly created Navigation in storage.
     * POST /navigations
     *
     * @param CreateNavigationAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateNavigationAPIRequest $request)
    {
        $input = $request->all();

        $navigation = $this->navigationRepository->create($input);

        return $this->sendResponse(new NavigationResource($navigation), 'Navigation saved successfully');
    }

    /**
     * Display the specified Navigation.
     * GET|HEAD /navigations/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Navigation $navigation */
        $navigation = $this->navigationRepository->find($id);

        if (empty($navigation)) {
            return $this->sendError('Navigation not found');
        }

        return $this->sendResponse(new NavigationResource($navigation), 'Navigation retrieved successfully');
    }

    /**
     * Update the specified Navigation in storage.
     * PUT/PATCH /navigations/{id}
     *
     * @param int $id
     * @param UpdateNavigationAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNavigationAPIRequest $request)
    {
        $input = $request->all();

        /** @var Navigation $navigation */
        $navigation = $this->navigationRepository->find($id);

        if (empty($navigation)) {
            return $this->sendError('Navigation not found');
        }

        $navigation = $this->navigationRepository->update($input, $id);

        return $this->sendResponse(new NavigationResource($navigation), 'Navigation updated successfully');
    }

    /**
     * Remove the specified Navigation from storage.
     * DELETE /navigations/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Navigation $navigation */
        $navigation = $this->navigationRepository->find($id);

        if (empty($navigation)) {
            return $this->sendError('Navigation not found');
        }

        $navigation->delete();

        return $this->sendSuccess('Navigation deleted successfully');
    }
    public function navigationByRole(Request $request)
    {
        try {
            $tree = $this->navigationRepository->navigationTree($request->get('roleID'), $request->get('selectedTenantID'));
            return $this->sendResponse($tree, '');
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
    public function getAllUserNav(Request $request)
    {
        $user = Auth::user();
        $role_id = $user->fetchUserRole->role_id;

        if ($request->tenantId != 'null') {
            $tenantId = $request->tenantId;
        } else {
            $tenants = Tenant::where('api_key', '=', $request->apiKey)->first();
            if (!empty($tenants)) {
                $tenantId = $tenants['id'];
            }
        }

        if (isset($tenantId)) {
            $userTenant = TenantService::getUserTenant($user, $tenantId);
        }

        try {
            $result = NavigationRole::where('role_id', $role_id)->with(['navigation' => function ($q) {
                $q->where('has_children', 0);
            }])
                ->whereHas('navigation', function ($q) {
                    $q->where('has_children', 0);
                })
                ->get();
            $nav = array();
            if (!empty($userTenant)) {
                if ($userTenant['kyc_status'] == 3) {
                    if (!empty($result)) {
                        foreach ($result as $val) {
                            array_push($nav, $val['navigation']);
                        }
                    }
                }
            }

            $rest['data'] = $nav;
            return $rest;
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
    public function getAllNavByType(Request $request)
    {
        $user = Auth::user();
        $role_id = $user->fetchUserRole->role_id;
        $role = Role::findById($role_id);
        $input = $request->all();
        $isBitTender = $input['is_bid_tender'];
        if ($isBitTender == 1) {
            return app()->make(NavigationService::class, ['role' => $role])->bidTenderHandle();
        }else {
            return app()->make(NavigationService::class, ['role' => $role])->handle();
        }
        return $input;
    }
}
