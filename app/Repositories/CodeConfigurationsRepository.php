<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\CodeConfigurations;
use App\Repositories\BaseRepository;
use App\Services\GeneralService;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\CrudOperations;
use Illuminate\Http\Request;

/**
 * Class CodeConfigurationsRepository
 * @package App\Repositories
 * @version November 11, 2024, 4:47 pm +04
*/

class CodeConfigurationsRepository extends BaseRepository
{
    /**
     * @var array
     */
    use CrudOperations;

    protected $fieldSearchable = [
        'uuid',
        'serialization_based_on',
        'code_pattern',
        'company_id',
        'company_system_id',
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
        return CodeConfigurations::class;
    }
    protected function getModel()
    {
        return new CodeConfigurations();
    }

    public function getAllCodeConfiguration(Request $request)
    {
        $input  = $request->all();
        $selectedCompanyID =  $input['selectedCompanyID'];

        $languages = $this->model->getAllCodeConfiguration($selectedCompanyID);
        return DataTables::eloquent($languages)
            ->addColumn('serialization', function ($row)
            {
                switch ($row->serialization_based_on)
                {
                    case 1:
                        return 'Contract';
                    default:
                        return '';
                }
            })
            ->addColumn('Actions', 'Actions', "Actions")
            ->addIndexColumn()
            ->make(true);
    }
    public function createCodeConfiguration($input)
    {
        $serializationBasedOn = $input['serialization_based_on'] ?? null;
        $codePattern = $input['code_pattern'] ?? null;
        $selectedCompanyID = $input['selectedCompanyID'] ?? null;
        $uuid = ContractManagementUtils::generateUuid(16);
        $checkUuidExists = CodeConfigurations::checkUniqueUuid($uuid);
        if($checkUuidExists)
        {
            GeneralService::sendException('Uuid already exists');
        }
        return DB::transaction(function() use ($serializationBasedOn, $codePattern, $selectedCompanyID, $uuid)
        {
            $companyID = General::getCompanyById($selectedCompanyID);
            $insertArray = [
                'uuid' => $uuid,
                'serialization_based_on' => $serializationBasedOn,
                'code_pattern' => $codePattern,
                'company_id' => $companyID,
                'company_system_id' => $selectedCompanyID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];
            CodeConfigurations::create($insertArray);
        });
    }
    public function updateCodeConfiguration($input, $id)
    {
        $serializationBasedOn = $input['serialization_based_on'] ?? null;
        $codePattern = $input['code_pattern'] ?? null;
        return DB::transaction(function() use ($serializationBasedOn, $codePattern, $id)
        {
            $updateArray = [
                'serialization_based_on' => $serializationBasedOn,
                'code_pattern' => $codePattern,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];
            CodeConfigurations::where('id', $id)->update($updateArray);
        });
    }
}
