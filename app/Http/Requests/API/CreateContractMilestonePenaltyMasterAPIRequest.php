<?php

namespace App\Http\Requests\API;

use App\Models\ContractMilestonePenaltyMaster;
use InfyOm\Generator\Request\APIRequest;

class CreateContractMilestonePenaltyMasterAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'selectedCompanyID' => 'required',
            'contractUuid' => 'required',
            'minimum_penalty_percentage' => 'required',
            'maximum_penalty_percentage' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'selectedCompanyID.required' => trans('common.company_id_is_required_dot'),
            'contractUuid.required' => trans('common.contract_code_is_required_dot'),
            'minimum_penalty_percentage.required' => trans('common.minimum_penalty_percentage_is_required_dot'),
            'maximum_penalty_percentage.required' => trans('common.Maximum_penalty_percentage_is_required_dot'),
        ];
    }
}
