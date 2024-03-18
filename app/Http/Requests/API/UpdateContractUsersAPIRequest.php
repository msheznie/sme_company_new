<?php

namespace App\Http\Requests\API;

use App\Models\ContractUsers;
use Illuminate\Validation\Rule;
use InfyOm\Generator\Request\APIRequest;

class UpdateContractUsersAPIRequest extends APIRequest
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
            'contractUserType' => 'required',
            'contractUserId' => 'required',
            'contractUserCode' => 'required',
            'contractUserName' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'selectedCompanyID.required' => trans('common.company_id_is_required'),
            'contractUserType.required' => trans('common.contract_user_type_is_required'),
            'contractUserId.required' => trans('common.contract_user_id_is_required'),
            'contractUserCode.required' => trans('common.contract_user_code_is_required'),
            'contractUserName.required' => trans('common.contract_user_name_is_required'),
        ];
    }
}
