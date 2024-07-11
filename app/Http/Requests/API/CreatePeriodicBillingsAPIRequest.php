<?php

namespace App\Http\Requests\API;

use App\Models\PeriodicBillings;
use InfyOm\Generator\Request\APIRequest;

class CreatePeriodicBillingsAPIRequest extends APIRequest
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
            'contractUuid' => 'required',
            'amount' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'occurrence_type' => 'required',
            'due_in' => [
                'required_if:occurrence_type,7',
                function ($attribute, $value, $fail) {
                    if ($this->input('occurrence_type') == 7 && $value <= 0) {
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
            'contractUuid.required' => 'Contract ID is required.',
            'amount.required' => 'amount is required.',
            'start_date.title' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
            'occurrence_type.required' => 'Valid occurrence is required.',
            'due_in.required_if' => 'Due in field is required.',
        ];
    }
}
