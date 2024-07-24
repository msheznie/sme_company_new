<?php

namespace App\Http\Requests\API;

use App\Models\ContractUsers;
use InfyOm\Generator\Request\APIRequest;
use Illuminate\Validation\Rule;

class CreateContractUsersAPIRequest extends APIRequest
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
            'userList' => 'required|array',
            'userType' => 'required',
            'selectedCompanyID' => 'required',
            'userList.*.contractUserId' => [
                'required',
                Rule::unique('cm_contract_users', 'contractUserId')
                    ->where('contractUserType', $this->input('userType'))
            ],
            'userList.*.contractUserCode' => 'required',
            'userList.*.contractUserName' => 'required'
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'userList.required' => trans('common.user_list_is_required'),
            'userList.array' => trans('common.user_list_must_be_an_array'),
            'userType.required' => trans('common.contract_user_type_is_required'),
            'selectedCompanyID.required' => trans('common.company_id_is_required'),
            'userList.*.contractUserId.required' => trans('common.contract_user_id_is_required'),
            'userList.*.contractUserId.unique' => trans('common.unique_contract_user_id'),
            'userList.*.contractUserCode.required' => trans('common.contract_user_code_is_required'),
            'userList.*.contractUserName.required' => trans('common.contract_user_name_is_required'),
        ];
    }

}
