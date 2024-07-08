<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\CMContractUserAssignAmd;
use App\Models\ContractMaster;
use App\Models\ContractUserAssign;
use App\Models\ContractUserGroup;
use App\Models\ContractUserGroupAssignedUser;
use App\Models\ContractUsers;
use App\Repositories\BaseRepository;
use App\Utilities\ContractManagementUtils;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
/**
 * Class ContractUserAssignRepository
 * @package App\Repositories
 * @version May 13, 2024, 5:42 am +04
*/

class ContractUserAssignRepository extends BaseRepository
{

    protected $userAssignedAmd;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->app = $app;
    }

    public function getContractUserAmdAssign()
    {
        if (!$this->userAssignedAmd)
        {
            $this->userAssignedAmd = $this->app->make(CMContractUserAssignAmdRepository::class);
        }
        return $this->userAssignedAmd;
    }

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'contractId',
        'userGroupId',
        'userId',
        'status',
        'createdBy',
        'updatedBy'
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
        return ContractUserAssign::class;
    }

    public function getAssignedUsers(Request $request) {
        $input  = $request->all();
        $companyId =  $input['selectedCompanyID'];
        $uuid =  $input['uuid'];
        $amendment = $input['amendment'];

     if($amendment)
        {
            $contractUserGroupList =  $this->getContractUserAmdAssign()->userAssignedData($companyId, $uuid);
        }else
        {
            $contractUserGroupList =  $this->model->getAssignedUsers($companyId, $uuid);
        }


        return DataTables::eloquent($contractUserGroupList)
            ->addColumn('Actions', 'Actions', "Actions")
            ->addIndexColumn()
            ->make(true);
    }

    public function createRecord($input)
    {
        $contractResult = ContractMaster::where('uuid', $input['contractuuid'])->first();
        if (!$contractResult) {
            return false;
        }

        $amendment = $input['amendment'];

        if($amendment)
        {
            $historyUuid = $input['historyUuid'];
            $historyData = ContractManagementUtils::getContractHistoryData($historyUuid);
            if (!$historyData)
            {
                return false;
            }
        }
        $modelClass = $amendment ? 'App\Models\CMContractUserAssignAmd' : 'App\Models\ContractUserAssign';


        $selectedUserGroupsUuid = array_column($input['selectedUserGroups'], 'id');

        $userGroupIdList = ContractUserGroup::select('id as i')
            ->whereIn('uuid', $selectedUserGroupsUuid)
            ->get()
            ->toArray();

        $groupIdList = array_column($userGroupIdList, 'i');

        $userIdsAssignedUserGroup = ContractUserGroupAssignedUser::select('contractUserId', 'userGroupId')
            ->whereIn('userGroupId', $groupIdList)
            ->where('status', 1)
            ->get();

        foreach ($userIdsAssignedUserGroup as $user) {
            $contractId = $contractResult->id;
            $userGroupId = $user['userGroupId'];
            $userId = $user['contractUserId'];
            $existingRecord = $modelClass::where('contractId', $contractId)
                ->where('userGroupId', $userGroupId)
                ->where('userId', $userId)
                ->where('status', 1);

                if ($amendment)
                {
                    $existingRecord->where('contract_history_id', $historyData->id);
                }
                $existingRecord = $existingRecord->first();

            if (!$existingRecord)
            {
                $input['uuid'] = bin2hex(random_bytes(16));
                $input['contractId'] = $contractId;
                $input['userGroupId'] = $userGroupId;
                $input['userId'] = $user['contractUserId'];
                $input['createdBy'] = General::currentEmployeeId();
                $input['updated_at'] = null;
                if($amendment)
                {
                    $input['contract_history_id'] = $historyData->id;
                }

                $modelClass::create($input);
            }
        }

        foreach ($input['selectedUsers'] as $user) {
            $contractId = $contractResult->id;
            $userGroupId = 0;
            $user = ContractUsers::select('id')
                ->where('uuid', $user['id'])
                ->first();

            if ($user) {
                $userId = $user->id;
                $existingRecord = $modelClass::where('contractId', $contractId)
                    ->where('userGroupId', $userGroupId)
                    ->where('userId', $userId)
                    ->where('status', 1);
                    if ($amendment)
                    {
                        $existingRecord->where('contract_history_id', $historyData->id);
                    }
                $existingRecord = $existingRecord->first();

                if (!$existingRecord) {
                    $newRecord = [
                        'uuid' => bin2hex(random_bytes(16)),
                        'contractId' => $contractId,
                        'userGroupId' => $userGroupId,
                        'userId' => $userId,
                        'status' => 1,
                        'createdBy' => General::currentEmployeeId(),
                        'updated_at' => null
                    ];

                    if($amendment)
                    {
                        $newRecord['contract_history_id'] = $historyData->id;
                    }

                    $modelClass::create($newRecord);
                }
            }
        }

        return true;
    }

    public function getUserAssignData($id)
    {
        return ContractUserAssign::where('contractId',$id)
            ->get();
    }
}
