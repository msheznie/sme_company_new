<?php

namespace App\Http\Requests\API;

use App\Models\ContractDeliverables;
use InfyOm\Generator\Request\APIRequest;

class CreateContractDeliverablesAPIRequest extends APIRequest
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
            'companySystemID' => 'required',
            'contractUuid' => 'required',
            'title' => 'required',
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'companySystemID.required' => trans('common.company_id_is_required_dot'),
            'contractUuid.required' => trans('common.contract_code_is_required_dot'),
            'title.required' => trans('common.deliverable_title_is_required_dot'),
            'description.required' => trans('common.description_is_required_dot'),
        ];
    }
}
