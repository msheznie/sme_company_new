<?php

namespace App\Repositories;

use App\Models\CompanyDocumentAttachment;
use App\Repositories\BaseRepository;

/**
 * Class CompanyDocumentAttachmentRepository
 * @package App\Repositories
 * @version May 22, 2024, 10:40 am +04
*/

class CompanyDocumentAttachmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'companySystemID',
        'companyID',
        'documentSystemID',
        'documentID',
        'docRefNumber',
        'isAttachmentYN',
        'sendEmailYN',
        'codeGeneratorFormat',
        'isAmountApproval',
        'isServiceLineAccess',
        'isServiceLineApproval',
        'isCategoryApproval',
        'blockYN',
        'enableAttachmentAfterApproval',
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
        return CompanyDocumentAttachment::class;
    }
}
