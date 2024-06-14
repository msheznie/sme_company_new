<?php

namespace App\Repositories;

use App\Exceptions\ContractCreationException;
use App\Helpers\General;
use App\Models\ContractAdditionalDocuments;
use App\Models\ContractDocument;
use App\Models\ContractHistory;
use App\Models\ContractMaster;
use App\Models\ContractMilestone;
use App\Models\ContractSettingDetail;
use App\Models\ContractSettingMaster;
use App\Models\ContractUserAssign;
use App\Models\ErpDocumentAttachments;
use App\Services\ContractHistoryService;
use App\Utilities\ContractManagementUtils;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\DB;
use Exception;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

/**
 * Class ContractHistoryRepository
 * @package App\Repositories
 * @version May 29, 2024, 2:49 pm +04
 */

class ContractHistoryRepository extends BaseRepository
{
    /**
     * @var
     */
    protected $fieldSearchable = [
        'category',
        'date',
        'end_date',
        'contract_id',
        'company_id',
        'contract_title',
        'created_date',
        'created_by'
    ];
    /**
     * @var ContractMaster
     */
    private $contractMaster;

    public function __construct(ContractMaster $contractMaster)
    {
        $this->contractMaster = $contractMaster;
    }

    /**
     * Return searchable fields
     *
     * @return
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
        return ContractHistory::class;
    }

    public function createContractHistory($request)
    {
        $input = $request->all();
        return DB::transaction(function () use ($input)
        {
            $contractUuid = $input['contractUuid'];
            $companyId = $input['companySystemId'];
            $categoryId = $input['contractCategoryId'];
            $currentContractDetails = ContractManagementUtils::checkContractExist($contractUuid, $companyId);
            if (!$currentContractDetails)
            {
                throw new ContractCreationException('Contract not found');
            }

            switch ($categoryId)
            {
                case 2:
                    return $this->createAddendumContract($input,$currentContractDetails,$companyId);
                case 4:
                case 6:
                     $this->createHistory($input, null, $currentContractDetails->id);
                     break;
                default:
                    return $this->createContract($input,$currentContractDetails,$companyId);
            }
        });
    }

    private function createAddendumContract($input,$currentContractDetails,$companyId)
    {
        $input = ContractHistoryService::convertAndFormatInputData($input,$currentContractDetails['id']);
        $mergedContractDetails = $this->mergeSelectedInputWithContractDetails($currentContractDetails, $input);
        return $this->createContract($input,$mergedContractDetails,$companyId);
    }

    private function createContract($input,$currentContractDetails,$companyId)
    {

        $categoryId = $input['contractCategoryId'];

        $contract = $this->createContractInfo($currentContractDetails, $companyId, $categoryId);
        $contractId = $contract['id'];
        $this->createContractSetting($contractId, $currentContractDetails);
        $this->createUserAssign($currentContractDetails['id'],$contractId);
        $this->addSectionWiseRecords($currentContractDetails['id'],$contractId,$companyId);
        $this->createHistory($input,$currentContractDetails['id'],$contractId);
        return $contract['uuid'];
    }


    private function mergeSelectedInputWithContractDetails($contractDetails, $input)
    {
        $contractDetailsArray = $contractDetails->toArray();
        foreach ($input as $key => $value)
        {
            $contractDetailsArray[$key] = $value;
        }
        return $contractDetailsArray;
    }

    private function createContractInfo($currentContractDetails, $companyId, $categoryId)
    {
        $contactMaster = new ContractMaster();
        $lastSerialNumber  = $contactMaster->getMaxContractId();
        $contractCode = ContractManagementUtils::generateCode($lastSerialNumber, 'CO');
        $uuid = ContractManagementUtils::generateUuid();

        $insert = [
            'contractCode' => $contractCode,
            'title' => $currentContractDetails['title'],
            'description' => $currentContractDetails['description'],
            'contractType' => $currentContractDetails["contractType"],
            'counterParty' => $currentContractDetails["counterParty"],
            'counterPartyName' => $currentContractDetails["counterPartyName"],
            'referenceCode' => $currentContractDetails["referenceCode"],
            'contractOwner' => $currentContractDetails["contractOwner"],
            'contractAmount' => $currentContractDetails["contractAmount"],
            'startDate' => $currentContractDetails["startDate"],
            'endDate' => $currentContractDetails["endDate"],
            'agreementSignDate' => $currentContractDetails["agreementSignDate"],
            'notifyDays' => $currentContractDetails["notifyDays"],
            'contractTermPeriod' => $currentContractDetails["contractTermPeriod"],
            'contractRenewalDate' => $currentContractDetails["contractRenewalDate"],
            'contractExtensionDate' => $currentContractDetails["contractExtensionDate"],
            'contractTerminateDate' => $currentContractDetails["contractTerminateDate"],
            'contractRevisionDate' => $currentContractDetails["contractRevisionDate"],
            'primaryCounterParty' => $currentContractDetails["primaryCounterParty"],
            'primaryEmail' => $currentContractDetails["primaryEmail"],
            'primaryPhoneNumber' => $currentContractDetails["primaryPhoneNumber"],
            'secondaryCounterParty' => $currentContractDetails["secondaryCounterParty"],
            'secondaryEmail' => $currentContractDetails["secondaryEmail"],
            'secondaryPhoneNumber' => $currentContractDetails["secondaryPhoneNumber"],
            'documentMasterId' => $currentContractDetails["documentMasterId"],
            'status' => 0,
            'uuid' => $uuid,
            'companySystemID' => $companyId,
            'created_by' => General::currentEmployeeId(),
            'created_at' => Carbon::now(),
            'parent_id' => $currentContractDetails['id']
        ];

        $categoryFields = [
            1 => 'is_amendment',
            2 => 'is_addendum',
            3 => 'is_renewal',
            4 => 'is_extension',
            5 => 'is_revision',
            6 => 'is_termination',
        ];

        if (isset($categoryFields[$categoryId]))
        {
            $insert[$categoryFields[$categoryId]] = 1;
        }

        try
        {
            $insertResponse = ContractMaster::create($insert);
            if (!$insertResponse)
            {
                throw new ContractCreationException('Something went wrong while creating the contract.');
            }
            return
                [
                    'id' => $insertResponse->id,
                    'uuid' => $insertResponse->uuid
                ];
        } catch (Exception $e)
        {
            throw new ContractCreationException('Failed to create contract header '.$e->getMessage());
        }
    }

    private function createContractSetting($contractId,$currentContractDetails)
    {
        $cloningContractId = $currentContractDetails['id'];

        try
        {
            $getMasterData = ContractSettingMaster::getSettingMaster($cloningContractId);
            $getMasterData->each(function ($master) use ($contractId, $cloningContractId)
            {
                $uuid = ContractManagementUtils::generateUuid();
                $masterRecord = [
                    'uuid' => $uuid,
                    'contractId' => $contractId,
                    'contractTypeSectionId' => $master['contractTypeSectionId'],
                    'isActive' => $master['isActive'],
                ];

                $masterModel = ContractSettingMaster::create($masterRecord);
                $detailRecords = ContractSettingDetail::getSettingDetails($cloningContractId, $master['id']);

                $detailData = $detailRecords->map(function ($detail) use ($contractId, $masterModel)
                {
                    $uuid = ContractManagementUtils::generateUuid();

                    return [
                        'uuid' => $uuid,
                        'contractId' => $contractId,
                        'settingMasterId' => $masterModel->id,
                        'sectionDetailId' => $detail['sectionDetailId'],
                        'isActive' => $detail['isActive'],
                        'created_at' => now(),
                    ];
                })->toArray();

                ContractSettingDetail::insert($detailData);
            });


        } catch (Exception $e)
        {
            throw new ContractCreationException('Failed to create contract settings '.$e->getMessage());
        }
    }

    private function createUserAssign($cloningContractId, $contractId)
    {
        try
        {
            $getUserAssignById = ContractUserAssign::getUserAssignDetailsByContractId($cloningContractId);

            foreach ($getUserAssignById as $details)
            {
                $uuid = ContractManagementUtils::generateUuid();
                $data = [
                    'uuid'=> $uuid,
                    'contractId'=> $contractId,
                    'userGroupId'=> $details['userGroupId'],
                    'userId'=> $details['userId'],
                    'status'=>$details['status'],
                    'createdBy'=> $details['createdBy'],
                    'created_at'=> $details['created_at'],
                    'updated_at' => $details['updated_at'],
                    'updatedBy' => $details['updatedBy'],
                    'isActive'=> $details['isActive'],
                ];

                ContractUserAssign::create($data);
            }

        }catch (Exception $e)
        {
            throw new ContractCreationException('Failed to create user settings '.$e->getMessage());
        }
    }

    private function addSectionWiseRecords($cloningContractId, $contractId,$companyId)
    {
        $createdIds = [];

        try
        {
            $records = ContractSettingMaster::getContractSettings($cloningContractId);

            foreach ($records as $record)
            {
                $cmSectionId = $record->contractTypeSection->cmSection_id ?? null;

                if ($cmSectionId)
                {
                    $createdIds = array_merge(
                        $createdIds, $this->cloneContractSettingMaster(
                        $cmSectionId, $cloningContractId, $contractId, $companyId
                    )
                    );
                }


                foreach ($record->contractSettingDetails as $detail)
                {
                    self::cloneContractSettingDetail($detail, $contractId, $cloningContractId);
                }

            }
        }
        catch (Exception $e)
        {
            throw new ContractCreationException('Failed to add section wise records: ' . $e->getMessage());
        }

        return $createdIds;
    }

    private function createSectionData($modelName, $cloningContractId, $contractId,  $additionalData = false)
    {
        $createdIds = [];

        try
        {
            $model = new $modelName();
            $table = $model->getTable();
            $columns = \Schema::getColumnListing($table);

            $contractColumn = self::getContractColumn($columns);

            $records = $model::where($contractColumn, $cloningContractId)->get();



            foreach ($records as $record)
            {
                $oldId = $record->id;
                $uuid = ContractManagementUtils::generateUuid();
                $recordData = $record->only($columns);
                unset
                (
                    $recordData['id'], $recordData['created_by'], $recordData['updated_by'],
                    $recordData['created_at'], $recordData['updated_at']
                );

                if (isset($additionalData['milestoneRetention']) && $additionalData['milestoneRetention'])
                {
                    $getMileStoneIdCloning = ContractMilestone::getMilestoneData
                    ($cloningContractId, $record->milestoneId);

                    $getMileStoneId = ContractMilestone::getMilestoneDataByTitle
                    ($contractId, $getMileStoneIdCloning->title);

                    $recordData['milestoneId'] = $getMileStoneId['id'];
                }
                $recordData['uuid'] = $uuid;
                $recordData[$contractColumn] = $contractId;
                $recordData['created_by'] = General::currentEmployeeId();
                $recordData['created_at'] = now();
                $newRecord = $model::create($recordData);
                $createdIds[$oldId] = $newRecord->id;
            }
        }
        catch (Exception $e)
        {
            throw new ContractCreationException("Failed to create data for model $modelName: " . $e->getMessage());
        }

        return $createdIds;
    }

    public function insertDocumentAttachment($documentIdMapping,$documentSystemID,$companySystemID)
    {

        try
        {
            $erpAttachmentModel = new ErpDocumentAttachments();
            $attachmentTable = $erpAttachmentModel->getTable();
            $attachmentColumns = \Schema::getColumnListing($attachmentTable);

            foreach ($documentIdMapping as $oldDocumentId => $newDocumentId)
            {

                $attachments = $erpAttachmentModel->getAttachmentDocumentWise
                (
                    $documentSystemID,$oldDocumentId,$companySystemID
                );

                if (!is_iterable($attachments))
                {
                    continue;
                }

                foreach ($attachments as $attachment)
                {
                    $attachmentData = $attachment->only($attachmentColumns);
                    unset($attachmentData['attachmentID']);
                    $attachmentData['documentSystemCode'] = $newDocumentId;
                    $erpAttachmentModel::create($attachmentData);
                }
            }
        } catch (Exception $e)
        {
            throw new ContractCreationException
            (
                "Failed to create data for document attachment table: " . $e->getMessage()
            );
        }

    }

    private function cloneContractSettingDetail($detail, $contractId, $cloningContractId)
    {
        $modelName = self::getModelNameForSectionDetail($detail->sectionDetailId);
        if ($modelName)
        {
            $additionalData = [];

            if ($modelName === 'App\\Models\\ContractMilestoneRetention')
            {
                $additionalData['milestoneRetention'] = true;

            }

            self::createSectionData($modelName, $cloningContractId, $contractId,  $additionalData);
        }
        return true;
    }

    private function getModelNameForSectionDetail($sectionDetailId)
    {
        switch ($sectionDetailId)
        {
            case 4:
                return 'App\\Models\\ContractOverallRetention';
            case 5:
                return 'App\\Models\\ContractMilestoneRetention';
            default:
                return null;
        }
    }

    private function createHistory($data,$cloningContractId,$contractId)
    {
        $contractCategoryId = $data['contractCategoryId'];
        $companySystemId = $data['companySystemId'];
        $uuid = ContractManagementUtils::generateUuid();
        try
        {
            $insert = [
                'category' => $contractCategoryId,
                'uuid' =>$uuid,
                'contract_id' =>$contractId,
                'cloning_contract_id' =>$cloningContractId,
                'company_id' => $companySystemId,
                'created_by' => General::currentEmployeeId(),
                'created_at' => now()
            ];

            if (isset($data['date']))
            {
                $insert['date'] = $data['date'];
            }

            if (isset($data['contractTerminationDate']))
            {
                $insert['date'] = ContractManagementUtils::convertDate($data['contractTerminationDate']);
            }

            if (isset($data['revisedEndDate']))
            {
                $insert['date'] = ContractManagementUtils::convertDate($data['revisedEndDate']);
            }

            if (isset($data['end_date']))
            {
                $insert['end_date'] = $data['end_date'];
            }

            if (isset($data['reason']))
            {
                $insert['comment'] = $data['reason'];
            }

            $insertResponse = ContractHistory::create($insert);
            if (!$insertResponse)
            {
                throw new ContractCreationException('Something went wrong while creating the contract history.');
            }
            if($contractCategoryId == 4 || $contractCategoryId == 6)
            {
                ContractHistoryService::
                confirmHistoryDocument($insertResponse->id,$contractId,$companySystemId,$contractCategoryId);
            }

        } catch (Exception $e)
        {
            throw new ContractCreationException('Failed to create contract history '.$e->getMessage());
        }
    }
    private function cloneContractSettingMaster($cmSectionId, $cloningContractId, $contractId, $companyId)
    {
        $createdIds = [];

        switch ($cmSectionId)
        {
            case 1:
                $createdIds = $this->cloneSectionBoqItems($cloningContractId, $contractId);
                break;
            case 2:
                $createdIds = $this->cloneSectionMilestoneAndDeliverables($cloningContractId, $contractId);
                break;
            case 11:
                $createdIds = $this->cloneSectionDocuments($cloningContractId, $contractId, $companyId);
                break;
            default:
                // No action needed for other section ids
                break;
        }

        return $createdIds;
    }

    private function cloneSectionBoqItems($cloningContractId, $contractId)
    {
        return $this->createSectionData('App\\Models\\ContractBoqItems', $cloningContractId, $contractId);
    }

    private function cloneSectionMilestoneAndDeliverables($cloningContractId, $contractId)
    {
        return array_merge(
            $this->createSectionData('App\\Models\\ContractMilestone', $cloningContractId, $contractId),
            $this->createSectionData('App\\Models\\ContractDeliverables', $cloningContractId, $contractId)
        );
    }

    private function cloneSectionDocuments($cloningContractId, $contractId, $companyId)
    {
        $documentIdMapping = $this->createSectionData('App\\Models\\ContractDocument', $cloningContractId, $contractId);
        $additionalDocumentIdMapping = $this->createSectionData(
            'App\\Models\\ContractAdditionalDocuments', $cloningContractId, $contractId
        );

        $createdIds = array_merge(array_values($documentIdMapping), array_values($additionalDocumentIdMapping));

        self::insertDocumentAttachment($documentIdMapping, 121, $companyId);
        self::insertDocumentAttachment($additionalDocumentIdMapping, 122, $companyId);

        return $createdIds;
    }

    public function getContractColumn($columns)
    {
        $contractColumn = null;
        if (in_array('contractId', $columns))
        {
            $contractColumn = 'contractId';
        } elseif (in_array('contractID', $columns))
        {
            $contractColumn = 'contractID';
        }
        elseif (in_array('contract_id', $columns))
        {
            return 'contract_id';
        }
        else
        {
            $contractColumn = 'null';
        }

        return $contractColumn;

    }

    public function getContractHistory(Request $request)
    {
        $input = $request->all();
        $contractUuid = $input['contractUuid'];
        $categoryId = $input['category'];
        $companySystemID = $input['selectedCompanyID'];

        $contract = ContractManagementUtils::checkContractExist($contractUuid, $companySystemID);

       $languages = ContractHistory::contractHistory($contract->id, $categoryId, $companySystemID);
        return DataTables::eloquent($languages)
            ->addColumn('Actions', 'Actions', "Actions")
            ->addIndexColumn()
            ->make(true);
    }
}
