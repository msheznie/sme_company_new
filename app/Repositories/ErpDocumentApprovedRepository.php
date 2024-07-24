<?php

namespace App\Repositories;

use App\Exceptions\CommonException;
use App\Models\CompanyDocumentAttachment;
use App\Models\ErpDocumentApproved;
use App\Models\ErpEmployeesDepartments;
use App\Repositories\BaseRepository;
use Yajra\DataTables\DataTables;

/**
 * Class ErpDocumentApprovedRepository
 * @package App\Repositories
 * @version May 22, 2024, 9:25 am +04
*/

class ErpDocumentApprovedRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'companySystemID',
        'companyID',
        'departmentSystemID',
        'departmentID',
        'serviceLineSystemID',
        'serviceLineCode',
        'documentSystemID',
        'documentID',
        'documentSystemCode',
        'documentCode',
        'documentDate',
        'approvalLevelID',
        'rollID',
        'approvalGroupID',
        'rollLevelOrder',
        'employeeSystemID',
        'employeeID',
        'docConfirmedDate',
        'docConfirmedByEmpSystemID',
        'docConfirmedByEmpID',
        'preRollApprovedDate',
        'approvedYN',
        'approvedDate',
        'approvedComments',
        'rejectedYN',
        'rejectedDate',
        'rejectedComments',
        'myApproveFlag',
        'isDeligationApproval',
        'approvedForEmpID',
        'isApprovedFromPC',
        'approvedPCID',
        'reference_email',
        'timeStamp',
        'status'
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
        return ErpDocumentApproved::class;
    }

    public function getApprovedRecords($selectedCompanyID, $documentSystemID, $ids)
    {

        $approveDetails = ErpDocumentApproved::documentApprovedList(
            $documentSystemID,
            $ids,
            $selectedCompanyID
        );

        $approvalGroupIDs = $approveDetails->where('approvedYN', 0)->pluck('approvalGroupID')->unique();

        $approvalLists = ErpEmployeesDepartments::getApprovalList($approvalGroupIDs, $selectedCompanyID,
            $documentSystemID);

        foreach ($approveDetails as $value)
        {
            if ($value['approvedYN'] == 0)
            {
                $companyDocument = CompanyDocumentAttachment::companyDocumentAttachment(
                    $selectedCompanyID,
                    $documentSystemID
                );

                if (empty($companyDocument))
                {
                    throw new CommonException(trans('common.policy_not_found'));
                }

                $value['approval_list'] = $approvalLists->get($value['approvalGroupID'], collect());
            }
        }

        return $approveDetails;
    }
}
