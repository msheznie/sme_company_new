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
            'contract_id.required' => 'Contract Code is required.',
            'milestone_title.required' => 'Milestone title is required.',
            'penalty_percentage.required' => 'Penalty percentage is required.',
            'penalty_start_date.title' => 'Penalty start date is required.',
            'penalty_frequency.required' => 'Penalty frequency is required.',
            'due_in.required_if' => 'Due in field is required.',
        ];
    }
}
