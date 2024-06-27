<?php

namespace App\Http\Requests\API;

use App\Models\PeriodicBillings;
use InfyOm\Generator\Request\APIRequest;

class UpdatePeriodicBillingsAPIRequest extends APIRequest
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
            'amount' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'occurrence_type' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'selectedCompanyID.required' => 'Company Id is required.',
            'amount.required' => 'amount is required.',
            'start_date.title' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
            'occurrence_type.required' => 'Occurrence is required.',
        ];
    }
}
