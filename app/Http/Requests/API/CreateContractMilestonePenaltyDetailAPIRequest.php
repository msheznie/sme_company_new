<?php

namespace App\Http\Requests\API;

use App\Models\ContractMilestonePenaltyDetail;
use InfyOm\Generator\Request\APIRequest;

class CreateContractMilestonePenaltyDetailAPIRequest extends APIRequest
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
            'milestone_title' => 'required',
            'penalty_percentage' => 'required',
            'penalty_start_date' => 'required',
            'penalty_frequency' => 'required',
            'due_in' => [
                'required_if:penalty_frequency,7',
                function ($attribute, $value, $fail)
                {
                    if ($this->input('penalty_frequency') == 7 && $value <= 0)
                    {
                        $fail(trans('common.the_due_in_field_must_be_greater_than_zero'));
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'selectedCompanyID.required' => trans('common.company_id_is_required_dot'),
            'contract_id.required' => trans('common.contract_code_is_required_dot'),
            'milestone_title.required' => trans('common.milestone_title_is_required_dot'),
            'penalty_percentage.required' => trans('common.penalty_percentage_is_required_dot'),
            'penalty_start_date.title' => trans('common.penalty_start_date_is_required_dot'),
            'penalty_frequency.required' => trans('common.penalty_frequency_is_required_dot'),
            'due_in.required_if' => trans('common.due_in_field_is_required_dot'),
        ];
    }
}
