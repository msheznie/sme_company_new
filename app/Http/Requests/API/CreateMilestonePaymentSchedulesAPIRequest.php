<?php

namespace App\Http\Requests\API;

use App\Models\MilestonePaymentSchedules;
use InfyOm\Generator\Request\APIRequest;

class CreateMilestonePaymentSchedulesAPIRequest extends APIRequest
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
            'contract_id' => 'required',
            'milestone_id' => 'required',
            'description' => 'required',
            'percentage' => 'required',
            'amount' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'selectedCompanyID.required' => trans('common.company_id_is_required_dot'),
            'contract_id.required' => trans('common.contract_code_is_required_dot'),
            'milestone_id.required' => trans('common.milestone_title_is_required_dot'),
            'description.title' => trans('common.description_is_required_dot'),
            'percentage.required' => trans('common.percentage_is_required_dot'),
            'amount.required' => trans('common.amount_is_required_dot'),
        ];
    }
}
