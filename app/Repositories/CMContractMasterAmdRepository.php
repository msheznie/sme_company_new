<?php

namespace App\Repositories;

use App\Exceptions\ContractCreationException;
use App\Helpers\General;
use App\Models\CMContractMasterAmd;
use App\Models\ContractMaster;
use App\Models\ContractUsers;
use App\Models\TenderFinalBids;
use App\Repositories\BaseRepository;
use App\Services\ContractAmendmentService;
use App\Utilities\ContractManagementUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Contracts\Foundation\Application;
/**
 * Class CMContractMasterAmdRepository
 * @package App\Repositories
 * @version July 1, 2024, 10:32 am +04
 */
class CMContractMasterAmdRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'contract_history_id',
        'uuid',
        'contractCode',
        'title',
        'description',
        'contractType',
        'counterParty',
        'counterPartyName',
        'referenceCode',
        'contractOwner',
        'contractAmount',
        'startDate',
        'endDate',
        'agreementSignDate',
        'contractTermPeriod',
        'contractRenewalDate',
        'contractExtensionDate',
        'contractTerminateDate',
        'contractRevisionDate',
        'primaryCounterParty',
        'primaryEmail',
        'primaryPhoneNumber',
        'secondaryCounterParty',
        'secondaryEmail',
        'secondaryPhoneNumber',
        'documentMasterId',
        'status',
        'companySystemID',
        'confirmed_yn',
        'confirmed_date',
        'confirm_by',
        'confirmed_comment',
        'rollLevelOrder',
        'refferedBackYN',
        'timesReferred',
        'approved_yn',
        'approved_by',
        'approved_date',
        'created_by',
        'updated_by',
        'is_amendment',
        'is_addendum',
        'is_renewal',
        'is_extension',
        'is_revision',
        'is_termination',
        'parent_id',
        'tender_id'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */

    protected $contractMasterRepo;

    public function __construct(ContractMasterRepository $contractMasterRepo, Application $app)
    {
        parent::__construct($app);
        $this->contractMasterRepo = $contractMasterRepo;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CMContractMasterAmd::class;
    }

    public function save($historyId, $currentContractDetails)
    {
        try
        {

            $recordData = $this->prepareRecordData($currentContractDetails);
            $recordData['contract_history_id'] = $historyId;
            return CMContractMasterAmd::create($recordData);
        } catch (\Exception $e)
        {
            throw new ContractCreationException('Failed to create contract amendment: ' . $e->getMessage());
        }
    }

    public function prepareRecordData($currentContractDetails)
    {
        $levelNo = $this->model->getLevelNo($currentContractDetails['id']);
        $recordData = $currentContractDetails->toArray();
        $recordData['level_no'] = $levelNo;
        $recordData['id'] = $currentContractDetails['id'];
        $recordData['contractType'] = $currentContractDetails['contractType'];
        $recordData['counterPartyName'] = $currentContractDetails['counterPartyName'];
        $recordData['created_by'] =General::currentEmployeeId();
        return array_intersect_key(
            $recordData,
            array_flip((new CMContractMasterAmd())->getFillable())
        );
    }


    public function saveInitialRecord($currentContractDetails)
    {
        try
        {
            $recordData = $this->prepareRecordData($currentContractDetails);
            return CMContractMasterAmd::create($recordData);
        } catch (\Exception $e)
        {
            throw new ContractCreationException('Failed to create original contract amendment: ' . $e->getMessage());
        }
    }

    public function getContractMasterData(Request $request)
    {
        $input = $request->input();
        $companyId = $input['selectedCompanyID'];
        $historyUuid = $input['historyUuid'];
        $getContractHistoryData = ContractManagementUtils::getContractHistoryData($historyUuid);
        if (!$getContractHistoryData)
        {
            throw new ContractCreationException('Record not found');
        }

        $contractMasterData = $this->model->getContractMasterData($getContractHistoryData->id);
        $contractMaster = $this->contractMasterRepo->unsetValues($contractMasterData);
        $userUuid = ContractUsers::getContractUserIdByUuid($contractMasterData['counterPartyNameUuid']);
        $response =
            $this->contractMasterRepo->getEditFormData($contractMasterData['counterParty'],$userUuid,$companyId);

        $editData = $contractMaster;
        $response['editData'] = $editData;

        return $response;
    }


}
