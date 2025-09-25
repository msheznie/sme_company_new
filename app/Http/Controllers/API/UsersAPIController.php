<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Helpers\General;
use App\Http\Requests\API\CreateUsersAPIRequest;
use App\Http\Requests\API\UpdateUsersAPIRequest;
use App\Http\Requests\LanguageRequest;
use App\Models\ERPLanguageMaster;
use App\Models\Users;
use App\Models\WebEmployeeProfile;
use App\Repositories\UsersRepository;
Use App\Repositories\NavigationUserGroupSetupRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\UsersResource;
use Response;

/**
 * Class UsersController
 * @package App\Http\Controllers\API
 */

class UsersAPIController extends AppBaseController
{
    /** @var  UsersRepository */
    private $usersRepository;
    private $navigationUserGroupSetup;

    public function __construct(UsersRepository $usersRepo, NavigationUserGroupSetupRepository $navigationUserGroupSetup)
    {
        $this->usersRepository = $usersRepo;
        $this->navigationUserGroupSetup = $navigationUserGroupSetup;
    }

    /**
     * Display a listing of the Users.
     * GET|HEAD /users
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $users = $this->usersRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(UsersResource::collection($users), 'Users retrieved successfully');
    }

    /**
     * Store a newly created Users in storage.
     * POST /users
     *
     * @param CreateUsersAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUsersAPIRequest $request)
    {
        $input = $request->all();

        $users = $this->usersRepository->create($input);

        return $this->sendResponse(new UsersResource($users), 'Users saved successfully');
    }

    /**
     * Display the specified Users.
     * GET|HEAD /users/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Users $users */
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            return $this->sendError('Users not found');
        }

        return $this->sendResponse(new UsersResource($users), 'Users retrieved successfully');
    }

    /**
     * Update the specified Users in storage.
     * PUT/PATCH /users/{id}
     *
     * @param int $id
     * @param UpdateUsersAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUsersAPIRequest $request)
    {
        $input = $request->all();

        /** @var Users $users */
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            return $this->sendError('Users not found');
        }

        $users = $this->usersRepository->update($input, $id);

        return $this->sendResponse(new UsersResource($users), 'Users updated successfully');
    }

    /**
     * Remove the specified Users from storage.
     * DELETE /users/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Users $users */
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            return $this->sendError('Users not found');
        }

        $users->delete();

        return $this->sendSuccess('Users deleted successfully');
    }

    public function getCurrentUser(Request $request)
    {
        $user = General::currentUser();
        $input = $request->all();

        $companySystemID = $input['selectedCompanyID'] ?? 0;
        if($companySystemID == 0){
            $companySystemID = $user['employee']['empCompanySystemID'] ?? 0;
        }

        $user = collect($user)->except([
            'uuid', 'created_at', 'updated_at',
            'employee.empTitle', 'employee.empInitial', 'employee.empSurname',
            'employee.serial', 'employee.empLeadingText', 'employee.empName_O', 'employee.empSurname_O',
            'employee.empFirstName', 'employee.empFirstName_O', 'employee.empFamilyName', 'employee.empFamilyName_O',
            'employee.empFatherName', 'employee.empFatherName_O', 'employee.empManagerAttached',
            'employee.empDateRegistered', 'employee.empTelOffice', 'employee.empTelMobile', 'employee.empLandLineNo',
            'employee.extNo', 'employee.empFax', 'employee.empEmail', 'employee.empLocation', 'employee.empDateTerminated',
            'employee.empLoginActive', 'employee.empActive', 'employee.userGroupID', 'employee.empCompanyID', 'employee.religion',
            'employee.isLoggedIn', 'employee.isLoggedOutFailYN', 'employee.logingFlag', 'employee.isSuperAdmin',
            'employee.discharegedYN', 'employee.isFinalSettlementDone', 'employee.hrusergroupID', 'employee.employmentType',
            'employee.isConsultant', 'employee.isTrainee', 'employee.is3rdParty', 'employee.3rdPartyCompanyName',
            'employee.gender', 'employee.designation', 'employee.nationality', 'employee.isManager', 'employee.isApproval',
            'employee.isDashBoard', 'employee.isAdmin', 'employee.isBasicUser', 'employee.ActivationCode',
            'employee.ActivationFlag', 'employee.isHR_admin', 'employee.isLock', 'employee.basicDataIngCount',
            'employee.opRptManagerAccess', 'employee.isSupportAdmin', 'employee.isHSEadmin', 'employee.excludeObjectivesYN',
            'employee.machineID', 'employee.timestamp', 'employee.createdFrom', 'employee.isNewPortal', 'employee.uuid',
            'employee.isEmailVerified'
        ])->toArray();
        
        $companySystemID;
        $user['companies'] = [];
        $user['tenant_id'] = $user['employee']['empCompanySystemID'] ?? 0;
        $user['name'] = $user['employee']['empName'] ?? '';
        $user['profile_url'] = $this->usersRepository->getEmployeeImage($user['employee_id']);
        $navigations = $this->navigationUserGroupSetup->userMenusByCompany($companySystemID, $user['employee_id']);
        $user['navigations'] = $navigations;
        $user['getActiveLangs'] =  $this->getAvailableLanguages();
        $user['userDefaultLanguage'] =  $this->usersRepository->getUserLanguage($user['employee_id']);
        return $user;
    }

    protected function getAvailableLanguages()
    {
        return ERPLanguageMaster::getActiveERPLanguages();
    }

    public static function getUserDefaultLanguage($authUserId)
    {
        try {
            return ['defLangValues' => []];
        } catch (\Exception $exception) {
            return ['defLangValues' => []];
        }
    }

    public function updateUserLanguage(LanguageRequest $request)
    {
        try
        {
            $data = $this->usersRepository->updateUserLanguage($request);
            return $this->sendResponse([],$data['message']);
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
    }
}
