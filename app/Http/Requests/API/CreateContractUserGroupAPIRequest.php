<?php

namespace App\Http\Requests\API;

use App\Models\ContractUserGroup;
use InfyOm\Generator\Request\APIRequest;

class CreateContractUserGroupAPIRequest extends APIRequest
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
            'groupName' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'selectedCompanyID.required' => trans('common.company_id_is_required_dot'),
            'groupName.required' => trans('common.user_group_name_is_required'),
        ];
    }
}
