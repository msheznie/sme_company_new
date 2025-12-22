<?php

namespace App\Repositories;

use App\Models\Alert;
use App\Repositories\BaseRepository;

/**
 * Class AlertRepository
 * @package App\Repositories
 * @version July 2, 2024, 11:17 am +04
*/

class AlertRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'companySystemID',
        'companyID',
        'empSystemID',
        'empID',
        'docSystemID',
        'docID',
        'docApprovedYN',
        'docSystemCode',
        'docCode',
        'alertMessage',
        'alertDateTime',
        'alertViewedYN',
        'alertViewedDateTime',
        'empName',
        'empEmail',
        'ccEmailID',
        'emailAlertMessage',
        'isEmailSend',
        'attachmentFileName',
        'timeStamp'
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
        return Alert::class;
    }
}
