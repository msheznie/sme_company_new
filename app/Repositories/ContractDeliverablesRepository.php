<?php

namespace App\Repositories;

use App\Models\ContractDeliverables;
use App\Repositories\BaseRepository;

/**
 * Class ContractDeliverablesRepository
 * @package App\Repositories
 * @version April 26, 2024, 2:39 pm +04
*/

class ContractDeliverablesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'contractID',
        'milestoneID',
        'description',
        'startDate',
        'endDate',
        'companySystemID',
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
        return ContractDeliverables::class;
    }

    public function getDeliverables($contractID, $companySystemID): array {
        $deliverables = ContractDeliverables::select('uuid', 'milestoneID', 'description', 'startDate', 'endDate')
            ->with([
                'milestone' => function ($q) {
                    $q->select('id', 'uuid');
                }
            ])
            ->where('contractID', $contractID)
            ->where('companySystemID', $companySystemID)
            ->get();
        $deliverablesArray = [];
        if ($deliverables) {
            foreach($deliverables as $key => $value) {
                $deliverablesArray[$key]['uuid'] = $value['uuid'];
                $deliverablesArray[$key]['description'] = $value['description'];
                $deliverablesArray[$key]['startDate'] = $value['startDate'];
                $deliverablesArray[$key]['endDate'] = $value['endDate'];
                $deliverablesArray[$key]['milestoneUuid'] = $value['milestone']['uuid'] ?? null;
            }
        }
        return $deliverablesArray;
    }
}
