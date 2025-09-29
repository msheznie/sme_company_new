<?php

namespace App\Http\Requests\API;

use App\Models\ContractOverallPenalty;
use InfyOm\Generator\Request\APIRequest;

class UpdateContractOverallPenaltyAPIRequest extends APIRequest
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
            'minimum_penalty_percentage' => 'required',
            'maximum_penalty_percentage' => 'required',
            'actual_percentage' => 'required',
            'actual_penalty_start_date' => 'required',
            'penalty_circulation_frequency' => 'required',
            'due_in' => [
                'required_if:penalty_circulation_frequency,7',
                function ($attribute, $value, $fail)
                {
                    if ($this->input('penalty_circulation_frequency') == 7 && $value <= 0)
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
            'minimum_penalty_percentage.required' => trans('common.minimum_penalty_percentage_is_required_dot'),
            'maximum_penalty_percentage.required' => trans('common.Maximum_penalty_percentage_is_required_dot'),
            'actual_percentage.required' => trans('common.actual_penalty_percentage_is_required_dot'),
            'actual_penalty_start_date.title' => trans('common.actual_penalty_start_date_is_required_dot'),
            'penalty_circulation_frequency.required' => trans('common.penalty_circulation_frequency_is_required_dot'),
            'due_in.required_if' => trans('common.due_in_field_is_required_dot'),
        ];
    }
}
