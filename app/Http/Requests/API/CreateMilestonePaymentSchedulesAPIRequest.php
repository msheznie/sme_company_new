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
            'selectedCompanyID.required' => 'Company ID is required.',
            'contract_id.required' => 'Contract Code is required.',
            'milestone_id.required' => 'Milestone title is required.',
            'description.title' => 'Description is required.',
            'percentage.required' => 'Percentage is required.',
            'amount.required' => 'Amount is required.',
        ];
    }
}
