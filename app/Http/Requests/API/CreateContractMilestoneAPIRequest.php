<?php

namespace App\Http\Requests\API;

use App\Models\ContractMilestone;
use Illuminate\Validation\Rule;
use InfyOm\Generator\Request\APIRequest;

class CreateContractMilestoneAPIRequest extends APIRequest
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
            'formData' => 'required',
            'companySystemID' => 'required',
            'contractUuid' => 'required',
            'formData.title' => 'required',
            'formData.description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'formData.required' => trans('common.please_fill_all_required_fields'),
            'companySystemID.required' => trans('common.company_id_is_required_dot'),
            'contractUuid.required' => trans('common.contract_code_is_required_dot'),
            'formData.title.required' => trans('common.milestone_title_is_required_dot'),
            'formData.description.required' => trans('common.description_is_required_dot'),
        ];
    }
}
