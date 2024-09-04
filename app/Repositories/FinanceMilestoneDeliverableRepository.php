<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\ContractDeliverables;
use App\Models\ContractMilestone;
use App\Models\FinanceDocuments;
use App\Models\FinanceMilestoneDeliverable;
use App\Repositories\BaseRepository;
use App\Services\GeneralService;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class FinanceMilestoneDeliverableRepository
 * @package App\Repositories
 * @version September 2, 2024, 9:06 am +04
*/

class FinanceMilestoneDeliverableRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'finance_document_id',
        'document_type',
        'document',
        'master_id',
        'company_id',
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
        return FinanceMilestoneDeliverable::class;
    }

    public function createFinanceMD($formData)
    {
        $selectedCompanyID = $formData['selectedCompanyID'];
        $document = $formData['document'];
        $documentType = $formData['document_type'];
        $deliverables = $formData['deliverables'] ?? [];
        $milestones = $formData['milestones'] ?? [];
        $financeUuid = $formData['financeUuid'] ?? null;

        $financeDocument = FinanceDocuments::checkFinanceDocumentExists($financeUuid);
        if (empty($financeDocument))
        {
            GeneralService::sendException('Finance document not found.');
        }

        return DB::transaction(function() use ($selectedCompanyID, $document, $documentType, $deliverables,
            $milestones, $financeDocument) {
            if ($document == 1)
            {
                $this->updateFinanceDocumentMilestone($financeDocument['id'], $milestones, $documentType, $document,
                    $selectedCompanyID, $deliverables);
            } else
            {
                $this->updateFinanceDocumentDeliverables($financeDocument['id'], $deliverables, $documentType,
                    $document, $selectedCompanyID);
            }
        });
    }
    private function updateFinanceDocumentMilestone($financeID, $milestones, $documentType, $document,
                                                    $selectedCompanyID, $deliverables)
    {
        $existingMilestones = FinanceMilestoneDeliverable::getExistsMilestoneDeliverables($financeID, 1)
            ->pluck('master_id')->toArray();
        $selectedIds = $this->validateAndExtractIds($milestones, ContractMilestone::class, 1);

        $this->syncMilestonesOrDeliverables($financeID, $selectedIds, $existingMilestones, $documentType,
            $document, $selectedCompanyID);

        $this->updateFinanceDocumentDeliverables($financeID, $deliverables, $documentType, 2,
            $selectedCompanyID);

        return true;
    }

    private function updateFinanceDocumentDeliverables($financeID, $deliverables, $documentType, $document,
                                                       $selectedCompanyID)
    {
        $existingDeliverables = FinanceMilestoneDeliverable::getExistsMilestoneDeliverables($financeID, 0)
            ->pluck('master_id')->toArray();
        $selectedIds = $this->validateAndExtractIds($deliverables, ContractDeliverables::class, 0);

        $this->syncMilestonesOrDeliverables($financeID, $selectedIds, $existingDeliverables, $documentType, $document,
            $selectedCompanyID);

        return true;
    }

    private function validateAndExtractIds($items, $model, $milestoneYN)
    {
        $ids = [];
        foreach ($items as $item)
        {
            $record = $milestoneYN == 1 ? $model::checkContractMilestoneExists($item['id']) :
                $model::checkExists($item['id']);
            if (empty($record))
            {
                GeneralService::sendException($model::getNotFoundMessage());
            }
            $ids[] = $record['id'];
        }
        return $ids;
    }

    private function syncMilestonesOrDeliverables($financeID, $selectedIds, $existingIds, $documentType, $document,
                                                  $selectedCompanyID)
    {
        $idsToAdd = array_diff($selectedIds, $existingIds);
        $idsToDelete = array_diff($existingIds, $selectedIds);

        foreach ($idsToAdd as $id)
        {
            FinanceMilestoneDeliverable::create([
                'uuid' => ContractManagementUtils::generateUuid(16),
                'finance_document_id' => $financeID,
                'document_type' => $documentType,
                'document' => $document,
                'master_id' => $id,
                'company_id' => $selectedCompanyID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ]);
        }

        if (!empty($idsToDelete))
        {
            FinanceMilestoneDeliverable::where('finance_document_id', $financeID)
                ->where('document_type', $documentType)
                ->where('document', $document)
                ->whereIn('master_id', $idsToDelete)
                ->delete();
        }
        return true;
    }
}
