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
            'selectedCompanyID.required' => 'Company Id is required.',
            'contractUuid.required' => 'Contract Code is required.',
            'minimum_penalty_percentage.required' => 'Minimum penalty percentage is required.',
            'maximum_penalty_percentage.required' => 'Maximum penalty percentage is required.',
        ];
    }
}
