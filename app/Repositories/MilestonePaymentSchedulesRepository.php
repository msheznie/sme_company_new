<?php

namespace App\Repositories;

use App\Models\MilestonePaymentSchedules;
use App\Repositories\BaseRepository;

/**
 * Class MilestonePaymentSchedulesRepository
 * @package App\Repositories
 * @version June 27, 2024, 9:13 am +04
*/

class MilestonePaymentSchedulesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'contract_id',
        'milestone_id',
        'description',
        'percentage',
        'amount',
        'payment_due_date',
        'actual_payment_date',
        'milestone_due_date',
        'currency_id',
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
        return MilestonePaymentSchedules::class;
    }
}
