<?php

namespace App\Services;


use App\Exceptions\ContractCreationException;
use App\Models\CMContractBoqItemsAmd;
use App\Models\CMContractDeliverableAmd;
use App\Models\CMContractDocumentAmd;
use App\Models\CMContractMasterAmd;
use App\Models\CMContractMileStoneAmd;
use App\Models\CMContractOverallRetentionAmd;
use App\Models\CMContractUserAssignAmd;
use App\Models\ContractAdditionalDocumentAmd;
use App\Models\ContractAdditionalDocuments;
use App\Models\ContractBoqItems;
use App\Models\ContractDeliverables;
use App\Models\ContractDocument;
use App\Models\ContractHistory;
use App\Models\ContractMaster;
use App\Models\ContractMilestone;
use App\Models\ContractOverallRetention;
use App\Models\ContractUserAssign;
use App\Models\ErpDocumentAttachments;
use App\Models\ErpDocumentAttachmentsAmd;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class ContractAmendmentService
{
    public function updateContractStatusAmendment($input)
    {
        try
        {
            return DB::transaction(function () use ($input)
            {
                $contractId = $input['contractId'];
                $companyId = $input['selectedCompanyID'];
                $contractHistoryUuid = $input['contractHistoryId'];
                $getContractData = ContractManagementUtils::checkContractExist($contractId, $companyId);
                $getContractHistoryData = ContractManagementUtils::getContractHistoryData($contractHistoryUuid);

                $contractId = $getContractData->id;
                $contractHistoryId = $getContractHistoryData->id;
                ContractHistoryService::insertHistoryStatus($contractId,1,$companyId,$contractHistoryId);
                self::updateContractMasterAmendmentData($contractId,$contractHistoryId);
                self::updateContractUserAndUserGroup($contractHistoryId);
                self::updateContractBoqItemsAmendmentData($contractHistoryId,$contractId);
                self::updateContractMileStonesAndDeliverables($contractHistoryId,$contractId);
                self::updateContractOverallRetention($contractHistoryId);
                self::updateContractDocument($contractHistoryId);
                ContractHistoryService::updateContractHistory($contractHistoryId,$companyId,1);
                self::updateAdditionalDocument($contractHistoryId);
            });

        } catch (\Exception $e)
        {
            throw new ContractCreationException(trans('common.failed_to_update_contract_status: ' . $e->getMessage()));
        }
    }

    public function updateContractMasterAmendmentData($contractId,$contractHistoryId)
    {
        $getContractMasterAmdRecords = CMContractMasterAmd::getContractMasterData($contractHistoryId);
        try
        {
            if (!$getContractMasterAmdRecords)
            {
                throw new ContractCreationException("No record found in contractmaster amendment: ");
            }

            $contractmasterRecord = ContractMaster::find($contractId);
            if (!$contractmasterRecord)
            {
                throw new ContractCreationException("No record found in contractmaster");
            }

            $attributes = $getContractMasterAmdRecords->getAttributes();
            unset($attributes['id'], $attributes['created_at'], $attributes['updated_at'] , $attributes['level_no']);
            $attributes['contractType'] = $attributes['contractType'];
            $attributes['counterPartyName'] = $attributes['counterPartyName'];
            $attributes['status'] = 1;
            $contractmasterRecord->update($attributes);
        }
        catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to update ContractMaster: " . $e->getMessage());
        }
    }

    public function updateContractUserAndUserGroup($contractHistoryId)
    {
        try
        {
            CMContractUserAssignAmd::where('contract_history_id', $contractHistoryId)
                ->whereNotNull('id')
                ->get()
                ->each(function ($amdRecord)
                {
                    $masterRecord = ContractUserAssign::find($amdRecord->id);

                    if ($masterRecord)
                    {
                        $masterRecord->fill($amdRecord->toArray());
                        $masterRecord->save();
                    }
                });

            $newRecords = CMContractUserAssignAmd::where('contract_history_id', $contractHistoryId)
                ->whereNull('id')
                ->get();

            $newRecords->each(function ($record)
            {
                $newRecord = new ContractUserAssign();
                foreach ($record->toArray() as $column => $value)
                {
                    if (!in_array($column, ['amd_id', 'id', 'contract_history_id']))
                    {
                        $newRecord->{$column} = $value;
                    }
                }
                $newRecord->save();
            });

        }
        catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to update Contract user assing: " . $e->getMessage());
        }
    }

    public function deleteContractBoqAmendment($data)
    {
        try
        {
            $getContractHistoryData = ContractManagementUtils::getContractHistoryData($data['contractHistoryId']);
            CMContractBoqItemsAmd::where('uuid',$data['uuid'])
                ->where('contract_history_id',$getContractHistoryData->id)
                ->delete();
        }
        catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to delete contract boq items: " . $e->getMessage());
        }
    }

    public function updateContractBoqItemsAmendmentData($contractHistoryId,$contractId)
    {
        try
        {
            $amdRecords = CMContractBoqItemsAmd::where('contract_history_id', $contractHistoryId)
                ->whereNotNull('id')
                ->get();

            $amdRecordIds = $amdRecords->pluck('id');

            $amdRecords->each(function ($amdRecord)
            {
                $masterRecord = ContractBoqItems::find($amdRecord->id);

                if ($masterRecord)
                {
                    $masterRecord->fill($amdRecord->toArray());
                    $masterRecord->save();
                }
            });


            ContractBoqItems::whereNotIn('id', $amdRecordIds)
                ->where('contractId',$contractId)
                ->delete();

            $newRecords = CMContractBoqItemsAmd::where('contract_history_id', $contractHistoryId)
                ->whereNull('id')
                ->get();

            $newRecords->each(function ($record)
            {
                $newRecord = new ContractBoqItems();
                foreach ($record->toArray() as $column => $value)
                {
                    if (!in_array($column, ['amd_id', 'id', 'contract_history_id']))
                    {
                        $newRecord->{$column} = $value;
                    }
                }
                $newRecord->save();
            });


        }
        catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to update Contract boq items: " . $e->getMessage());
        }
    }

    public function deleteMilestoneAmd($input)
    {

        try
        {
            $contractMileStone = CMContractMileStoneAmd::getContractMilestone($input['uuid']);
            $contractDeliverable = CMContractDeliverableAmd::getContractDeliverables($contractMileStone->id);
            if($contractDeliverable)
            {
                throw new ContractCreationException(trans('common.linked_milestone_delete_error_message'));

            }

            $getMilestoneData = ContractManagementUtils::getMilestonesAmd($input['contractHistoryUuid'],$input['uuid']);
            CMContractMileStoneAmd::where('amd_id',$getMilestoneData->amd_id)
                ->delete();
        }
        catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to delete Contract milestone: " . $e->getMessage());
        }
    }

    public static function getAmendmentDeliverables($historyId)
    {
        $deliverables = CMContractDeliverableAmd::where('contract_history_id', $historyId)
            ->with(['milestone'])
            ->get();

        $deliverablesArray = [];
        if ($deliverables)
        {
            foreach($deliverables as $key => $value)
            {
                $deliverablesArray[$key]['uuid'] = $value['uuid'];
                $deliverablesArray[$key]['description'] = $value['description'];
                $deliverablesArray[$key]['dueDate'] = $value['dueDate'];
                $deliverablesArray[$key]['milestoneUuid'] = $value['milestone']['uuid'] ?? null;
                $deliverablesArray[$key]['title'] = $value['title'];
            }
        }
        return $deliverablesArray;


    }

    public static function getAmendmentMilestones($historyId)
    {
        return CMContractMileStoneAmd::where('contract_history_id', $historyId)->get();
    }

    public function deleteContractDeliverablesAmd($input)
    {
        try
        {
            $getContractHistory = ContractManagementUtils::getContractHistoryData($input['contractHistoryUuid']);
            CMContractDeliverableAmd::where('contract_history_id',$getContractHistory->id)
                ->where('uuid',$input['uuid'])
                ->delete();
        }
        catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to delete Contract deliverables: " . $e->getMessage());
        }
    }

    public function updateContractMileStonesAndDeliverables($historyId,$contractId)
    {

        try
        {
            $amdRecords = CMContractMileStoneAmd::where('contract_history_id', $historyId)
                ->whereNotNull('id')
                ->get();

            $amdRecordIds = $amdRecords->pluck('id');

            $amdRecords->each(function ($amdRecord)
            {
                $masterRecord = ContractMilestone::find($amdRecord->id);

                if ($masterRecord)
                {
                    $masterRecord->fill($amdRecord->toArray());
                    $masterRecord->save();
                }
            });


            ContractMilestone::whereNotIn('id', $amdRecordIds)
                ->where('contractId',$contractId)
                ->delete();


            $amdRecords = CMContractDeliverableAmd::where('contract_history_id', $historyId)
                ->whereNotNull('id')
                ->get();

            $amdRecordIds = $amdRecords->pluck('id');

            $amdRecords->each(function ($amdRecord)
            {
                $masterRecord = ContractDeliverables::find($amdRecord->id);

                if ($masterRecord)
                {
                    $recordArray = $amdRecord->toArray();
                    if (isset($recordArray['formattedDueDate']) && $recordArray['formattedDueDate'])
                    {
                        $recordArray['dueDate'] = Carbon::parse
                        (
                            $recordArray['formattedDueDate']
                        )->format('Y-m-d H:i:s');
                    }
                    $masterRecord->fill($recordArray);
                    $masterRecord->save();
                }
            });

            ContractDeliverables::whereNotIn('id', $amdRecordIds)
                ->where('contractId',$contractId)
                ->delete();

        }
        catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to update Contract deliverable: " . $e->getMessage());
        }

    }

    public static function getContractAmendment($uuid, $historyId, $contractExist = false)
    {
        $query = CMContractMasterAmd::where('uuid',$uuid);
        if($contractExist)
        {
            $query->whereNull('contract_history_id');
        }else
        {
            $query->where('contract_history_id', $historyId);
        }

        return $query->first();
    }

    public function updateContractOverallRetention($contractHistoryId)
    {
        try
        {
            CMContractOverallRetentionAmd::where('contract_history_id', $contractHistoryId)
                ->get()
                ->each(function ($amdRecord)
                {
                    $masterRecord = ContractOverallRetention::find($amdRecord->retention_id);

                    if ($masterRecord)
                    {
                        $recordArray = $amdRecord->toArray();
                        if (isset($recordArray['formatDueDate']) && $recordArray['formatDueDate'])
                        {
                            $recordArray['dueDate'] = Carbon::parse
                            (
                                $recordArray['formatDueDate']
                            )->format('Y-m-d H:i:s');
                        }

                        if (isset($recordArray['formatStartDate']) && $recordArray['formatStartDate'])
                        {
                            $recordArray['startDate'] = Carbon::parse
                            (
                                $recordArray['formatStartDate']
                            )->format('Y-m-d H:i:s');
                        }

                        $masterRecord->fill($recordArray);
                        $masterRecord->save();
                    }
                });

            $newRecords = CMContractOverallRetentionAmd::where('contract_history_id', $contractHistoryId)
                ->whereNull('retention_id')
                ->get();

            $newRecords->each(function ($record)
            {
                $newRecord = new ContractOverallRetention();
                foreach ($record->toArray() as $column => $value)
                {
                    if (!in_array($column, ['retention_id', 'id', 'contract_history_id']))
                    {
                        $newRecord->{$column} = $value;
                    }
                }
                $newRecord->save();
            });
        }
        catch (\Exception $e)
        {
            throw new ContractCreationException("Failed to update Contract deliverable: " . $e->getMessage());
        }

    }

    public function getContractDocumentAmend($uuid)
    {
        return CMContractDocumentAmd::where('uuid',$uuid)
            ->first();
    }

    public function getContractData($input)
    {
        $getContractHistoryData = ContractManagementUtils::getContractHistoryData($input['contractHistoryUuid']);
        $contractDocumentData = CMContractDocumentAmd::getContractDocumentData
        (
            $input['contractDocumentUuid'],$getContractHistoryData->id
        );

        $contractDocumentData['time'] = $contractDocumentData['receivedDate'] ?? null;
        $contractDocumentData['returnedTime'] = $contractDocumentData['returnedDate'] ?? null;

        $data['editData'] =$contractDocumentData;
        $data['documentReceivedFormat'] = ContractManagementUtils::getDocumentReceivedFormat();

        return $data;
    }



    public function updateContractDocument($historyId)
    {
        try
        {
            $getContractDocument = CMContractDocumentAmd::getcontractDocumentDataAmd($historyId);

            foreach ($getContractDocument as $record)
            {
                $recordData = $record->toArray();
                $newRecord = ContractDocument::create($recordData);

                try
                {
                    $this->insertErpDocumentAmd($newRecord->id, $record['id'], $historyId, 'COD');
                }
                catch (\Exception $e)
                {
                    throw new ContractCreationException("Contract document failed: " . $e->getMessage());
                }

            }
        } catch (\Exception $e)
        {
            throw new ContractCreationException("Contract document failed: " . $e->getMessage());
        }
    }

    private function insertErpDocumentAmd($newContractId, $oldContractId, $historyId, $documentCode)
    {
        $erpDocuments = ErpDocumentAttachmentsAmd::getErpAttachedData($documentCode, [$oldContractId]);

        foreach ($erpDocuments as $erpDocument)
        {
            $newErpDocument = $erpDocument->replicate();
            $newErpDocument->documentSystemCode = $newContractId;
            $newErpDocument->contract_history_id = $historyId;
            $newErpDocument->attachmentID = $erpDocument->attachmentID;
            ErpDocumentAttachments::create($newErpDocument->toArray());
        }
    }

    public function getHistoryData($input)
    {
        $getContractData = ContractManagementUtils::checkContractExist($input['contractUuid'], $input['selectedCompanyID']);
        $getHistoryData = ContractManagementUtils::getContractHistoryData($input['historyUuid']);
        $contractId = $getContractData->id;
        $historyId = $getHistoryData->id;

        $allChanges = [];
        $getSectionConfigs = self::sectionConfig();

        foreach ($getSectionConfigs as $sectionName => $config)
        {
            $modelName = $config['modelName'];
            $skippedFields = $config['skippedFields'] ?? [];
            $fieldDescriptions = $config['fieldDescriptions'] ?? [];
            $fieldMappings = $config['fieldMappings'] ?? [];

            $currentChanges = self::compareSection(
                $contractId,
                $historyId,
                $sectionName,
                $modelName,
                $skippedFields,
                $fieldDescriptions,
                $fieldMappings
            );

            $allChanges = array_merge($allChanges, $currentChanges);
        }

        return $allChanges;
    }

    private function compareSection(
        $contractId, $historyId, $sectionName, $modelName, $skippedFields, $fieldDescriptions, $fieldMappings
    )
    {
        $changes = [];

        $currentRecord = self::getCurrentData($modelName, $historyId, $contractId);

        if ($currentRecord)
        {
            $previousRecord = self::getPreviousRecords($modelName, $contractId, $currentRecord);

            $fillableFields = (new $modelName())->getFillable();

            $comparisonFunctions = [
                'startDate' => function($previous, $current)
                {
                    return Carbon::parse($previous)->toDateString() !== Carbon::parse($current)->toDateString();
                },
                'endDate' => function($previous, $current)
                {
                    return Carbon::parse($previous)->toDateString() !== Carbon::parse($current)->toDateString();
                },
                'agreementSignDate' => function($previous, $current)
                {
                    return Carbon::parse($previous)->toDateString() !== Carbon::parse($current)->toDateString();
                },
            ];

            foreach ($fillableFields as $field)
            {
                if (in_array($field, $skippedFields))
                {
                    continue;
                }

                $description = $fieldDescriptions[$field] ?? $field;

                $oldValue = $previousRecord->$field;
                $newValue = $currentRecord->$field;

                if (isset($fieldMappings[$field]))
                {
                    $model = $fieldMappings[$field]['model'];
                    $attribute = $fieldMappings[$field]['attribute'] ?? 'id';
                    $colName = $fieldMappings[$field]['colName'] ?? 'id';

                    $oldValue = $model::where($colName, $previousRecord->$field)->value($attribute);
                    $newValue = $model::where($colName, $currentRecord->$field)->value($attribute);
                }

                if (in_array($field, ['startDate', 'endDate', 'agreementSignDate']))
                {
                    $oldValue = Carbon::parse($oldValue)->toDateString();
                    $newValue = Carbon::parse($newValue)->toDateString();
                }


                $isDifferent = array_key_exists($field, $comparisonFunctions)
                    ? $comparisonFunctions[$field]($previousRecord->$field, $currentRecord->$field)
                    : $oldValue != $newValue;

                if ($isDifferent)
                {
                    $changes[] = [
                        'section' => $sectionName,
                        'field' => $description,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                    ];
                }
            }
        }

        return $changes;
    }

    public function sectionConfig()
    {
        return [
            'Contract Info' => [
                'modelName' => CMContractMasterAmd::class,
                'skippedFields' => ['level_no', 'contract_history_id'],
                'fieldDescriptions' => [
                    'title' => 'Title',
                    'startDate' => 'Contract Start Date',
                    'endDate' => 'Contract End Date',
                    'contractType' => 'Contract Type',
                    'contractAmount' => 'Contract Amount',
                    'referenceCode' => 'Reference Code',
                    'contractOwner' => 'Contract Owner',
                    'counterParty' => 'Counter Party',
                    'counterPartyName' => 'Counter Party Name',
                    'description' => 'Description',
                    'primaryCounterParty' => 'Primary Counter Party',
                    'primaryEmail' => 'Primary Email Address',
                    'primaryPhoneNumber' => 'Primary Phone Number',
                    'secondaryCounterParty' => 'Secondary Counter Party',
                    'secondaryEmail' => 'Secondary Email Address',
                    'secondaryPhoneNumber' => 'Secondary Phone Number',
                    'agreementSignDate' => 'Agreement Sign Date',
                    'contractTermPeriod' => 'Contract Term Period',
                    'notifyDays' => 'Notify Days',
                ],
                'fieldMappings' => [
                    'contractType' => [
                        'model' => \App\Models\CMContractTypes::class,
                        'attribute' => 'cm_type_name',
                        'colName'=> 'contract_typeId',
                    ],
                    'contractOwner' => [
                        'model' => \App\Models\ContractUsers::class,
                        'attribute' => 'contractUserName',
                        'colName'=> 'id',
                    ],
                    'counterParty' => [
                        'model' => \App\Models\CMCounterPartiesMaster::class,
                        'attribute' => 'cmCounterParty_name',
                        'colName'=> 'cmCounterParty_id',
                    ],
                    'counterPartyName' => [
                        'model' => \App\Models\ContractUsers::class,
                        'attribute' => 'contractUserName',
                        'colName'=> 'id',
                    ],
                ],


            ],
        ];
    }

    public function getCurrentData($modelName, $historyId, $contractId)
    {
        return $modelName::where('contract_history_id', $historyId)
            ->where('id', $contractId)
            ->first();
    }

    public function getPreviousRecords($modelName, $contractId, $currentRecord)
    {
        return $modelName::where('id', $contractId)
            ->where('level_no', '<', $currentRecord->level_no)
            ->orderBy('level_no', 'desc')
            ->first();
    }

    public static function getcontractDocumentDataAmd($historyId, $uuid , $allRecords = false)
    {
        $query = CMContractDocumentAmd::where('contract_history_id', $historyId);

        if ($allRecords)
        {
            $data = $query->get();
        } else
        {
            $data = $query->where('uuid', $uuid)->first();
        }

        return $data;
    }

    public function additionalDocumentList($companyId,$contractUuid,$historyUuid)
    {
         $contractMaster = ContractManagementUtils::checkContractExist($contractUuid,$companyId);
         $contractHistory = ContractManagementUtils::getContractHistoryData($historyUuid);

        return ContractAdditionalDocumentAmd::select('id', 'documentMasterID', 'uuid', 'documentType', 'documentName',
            'documentDescription', 'expiryDate', 'additional_doc_id')
            ->with([
                'documentMaster' => function ($query)
                {
                    $query->select('id', 'uuid', 'documentType');
                },
                'attachment' => function ($query)
                {
                    $query->select(
                        DB::raw('id as attachmentID'), 'documentSystemID',
                        'documentSystemCode', 'myFileName'
                    );
                }
            ])
            ->where([
                'contractID' => $contractMaster->id,
                'contract_history_id' => $contractHistory->id
            ])
            ->orderBy('id', 'desc');
    }

    public static function getContractAdditionalDocument($uuid,$historyUuid, $allRecords = false)
    {
        if ($allRecords)
        {
            return ContractAdditionalDocumentAmd::where('contract_history_id', $historyUuid)->get();
        }

        $getHistoryData = ContractManagementUtils::getContractHistoryData($historyUuid);
        return ContractAdditionalDocumentAmd::where([
            ['uuid', $uuid],
            ['contract_history_id', $getHistoryData->id]
        ])->first();

    }

    public static function getAdditionalDocument($input)
    {
        $additionalDocumentUuid = $input['additionalDocumentUuid'];
        $contractHistoryUuid = $input['contractHistoryUuid'];
        $contractHistoryData = ContractManagementUtils::getContractHistoryData($contractHistoryUuid);

        return ContractAdditionalDocumentAmd::getAdditionalDocument($additionalDocumentUuid, $contractHistoryData->id);
    }

    public static function updateAdditionalDocument($historyId)
    {
        try
        {
            $getContractDocument = ContractAdditionalDocumentAmd::getAdditionalDocumentDataAmd($historyId);

            foreach ($getContractDocument as $record)
            {
                $recordData = $record->toArray();
                $newRecord = ContractAdditionalDocuments::create($recordData);

                try
                {
                    self::insertErpDocumentAmd($newRecord->id, $record['id'], $historyId, 'COAD');
                }
                catch (\Exception $e)
                {
                    throw new ContractCreationException("Contract additional document failed: " . $e->getMessage());
                }

            }
        } catch (\Exception $e)
        {
            throw new ContractCreationException("Contract additional document failed: " . $e->getMessage());
        }
    }
}
