<?php

namespace App\Repositories;

use App\Helpers\General;
use App\Models\ContractMaster;
use App\Models\TemplateMaster;
use App\Repositories\BaseRepository;
use App\Services\GeneralService;
use App\Services\ContractMasterService;
use App\Utilities\ContractManagementUtils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\CrudOperations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Class TemplateMasterRepository
 * @package App\Repositories
 * @version February 21, 2025, 2:27 pm +04
*/

class TemplateMasterRepository extends BaseRepository
{
    /**
     * @var array
     */
    use CrudOperations;
    protected $fieldSearchable = [
        'id',
        'uuid',
        'contract_id',
        'content',
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
        return TemplateMaster::class;
    }

    protected function getModel()
    {
        return new TemplateMaster();
    }

    public function createTemplateMaster(Request $request){

        $input = $request->all();
        return DB::transaction(function () use ($input)
        {
            $companySystemID = $input['companySystemID'] ?? 0;
            $contractUUID = $input['contractUUID'] ?? null;
            $fileName = $input['fileName'] ?? null;
            $fileData = base64_decode($input['fileData']) ?? null;

            $contract = ContractManagementUtils::checkContractExist($contractUUID, $companySystemID);
            if(empty($contract))
            {
                GeneralService::sendException(trans('common.contract_code_not_found'));
            }
            $contractID = $contract['id'];
            $checkTemplateExists = TemplateMaster::checkTemplateExists($contractID, $companySystemID);
            if($checkTemplateExists)
            {
                GeneralService::sendException('Template master already exists for this contract.');
            }
            $uuid = ContractManagementUtils::generateUuid(16);
            $uuidExists = TemplateMaster::checkUuidExists($uuid);
            if ($uuidExists)
            {
                GeneralService::sendException('Uuid already exists');
            }

            $folderPath = public_path('template');
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }

            $path = $folderPath . '/' . $fileName;
            File::put($path, $fileData);

            $insertArray = [
                'uuid' => $uuid,
                'contract_id' => $contractID,
                'content' => 'template/' . $fileName,
                'company_id' => $companySystemID,
                'created_by' => General::currentEmployeeId(),
                'created_at' => Carbon::now()
            ];
            return TemplateMaster::insert($insertArray);
        });
    }

    public function updateTemplateMaster($input, $id)
    {
        return DB::transaction(function () use ($input, $id)
        {
            $content = $input['content'] ?? null;

            $updateArray = [

                'content' => $content,
                'updated_by' => General::currentEmployeeId(),
                'updated_at' => Carbon::now()
            ];
            return TemplateMaster::where('id', $id)->update($updateArray);
        });
    }
}
