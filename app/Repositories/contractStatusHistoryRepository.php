<?php

namespace App\Repositories;

use App\Exports\ContractManagmentExport;
use App\Helpers\CreateExcel;
use App\Helpers\General;
use App\Models\contractStatusHistory;
use App\Repositories\BaseRepository;
use App\Services\GeneralService;
use App\Utilities\ContractManagementUtils;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
/**
 * Class contractStatusHistoryRepository
 * @package App\Repositories
 * @version June 26, 2024, 10:27 am +04
*/

class contractStatusHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'contract_id',
        'contract_history_id',
        'status',
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
        return contractStatusHistory::class;
    }

    public function getContractListStatus(Request $request)
    {
        $input = $request->all();
        $getContractData = ContractManagementUtils::checkContractExist
        ($input['contractUuid'],$input['selectedCompanyID']);


        return $this->model->getContractListStatus($getContractData->id);
    }

    public function getContractStatusHistory($request)
    {
        try
        {
            $input = $request->all();
            $contractId = $input['contractId'];
            $companyId = $input['selectedCompanyID'];
            $contractData = ContractManagementUtils::checkContractExist(
                $contractId, $companyId
            );

            if (!$contractData)
            {
                GeneralService::sendException('Contract Not Found');
            }


            return $this->model->getContractStatusHistory($contractData->id,$companyId);
        }
        catch (\Exception $ex)
        {
            GeneralService::sendException('Failed to get Document tracing data', $ex);
        }
    }

    public function getContractHistoryStatusCount(Request $request) {
        $input  = $request->all();
        $companyId =  $input['selectedCompanyID'];
        return contractStatusHistory::getContractStatusCounts($companyId);
    }

    public function exportContractStatusHistory(Request $request)
    {
        try
        {
            $input = $request->all();
            $type = $input['type'];
            $disk = $input['disk'];
            $docName = $input['doc_name'];
            $contractUuid = $input['contractUuid'];
            $companySystemID = $input['selectedCompanyID'] ?? 0;

            $companyCode = $companySystemID > 0 ? General::getCompanyById($companySystemID) : 'common';
            $detailArray = [
                'company_code' => $companyCode
            ];

            $contract = ContractManagementUtils::checkContractExist($contractUuid, $companySystemID);

            if(!$contract)
            {
                GeneralService::sendException('Contract Not Found');
            }

            $contractHistoryData = self::exportContractHistoryStatusReport($contract['id'],$companySystemID);

            $export = new ContractManagmentExport($contractHistoryData);
            $basePath = CreateExcel::process($type, $docName, $detailArray, $export, $disk);

            return $basePath;

        }
        catch (\Exception $e)
        {
            GeneralService::sendException('Failed to generate the excel : ', $e);
        }

    }

    public function exportContractHistoryStatusReport($contractId, $companySystemID)
    {
        $contractStatusData = $this->model->getContractStatusHistory($contractId, $companySystemID);
        $data = [
            ['Status', 'Contract Code', 'Contract Title', 'Action By', 'Action Date']
        ];

        // Populate data rows
        if ($contractStatusData)
        {
            foreach ($contractStatusData as $value)
            {
                $data[] = [
                    'Status' => ContractManagementUtils::getContractStatus($value['status']),
                    'Contract Code' => isset($value['contractCode'])
                        ?
                        preg_replace('/^=/', '-', $value['contractCode'])
                        :
                        '-',
                    'Contract Title' => isset($value['title'])
                        ?
                        preg_replace('/^=/', '-', $value['title'])
                        :
                        '-',
                    'Action By' => $value['systemUser'] ?? false
                        ? 'System User'
                        :
                        (
                            isset($value['empName'])
                            ?
                            preg_replace('/^=/', '-', $value['empName']) :
                            '-'
                        ),
                    'Action Date' => isset($value['createdAt'])
                        ?
                        (new \DateTime($value['createdAt']))->format('Y-m-d')
                        :
                        '-',
                ];
            }
        }

        return $data;
    }
}
