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
                        $fail('The due in field must be greater than 0.');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'selectedCompanyID.required' => 'Company Id is required.',
            'minimum_penalty_percentage.required' => 'Minimum penalty percentage is required.',
            'maximum_penalty_percentage.required' => 'Maximum penalty percentage is required.',
            'actual_percentage.required' => 'Actual penalty percentage is required.',
            'actual_penalty_start_date.title' => 'Actual penalty start date is required.',
            'penalty_circulation_frequency.required' => 'Penalty circulation frequency is required.',
            'due_in.required_if' => 'Due in field is required.',
        ];
    }
}
