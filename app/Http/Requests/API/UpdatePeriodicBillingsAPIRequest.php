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
            'due_in' => [
                'required_if:occurrence_type,7',
                function ($attribute, $value, $fail) {
                    if ($this->input('occurrence_type') == 7 && $value <= 0) {
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
            'amount.required' => trans('common.amount_is_required_dot'),
            'start_date.title' => trans('common.start_date_is_required_dot'),
            'end_date.required' => trans('common.end_date_is_required_dot'),
            'occurrence_type.required' => trans('common.valid_occurrence_is_required_dot'),
            'due_in.required_if' => trans('common.due_in_field_is_required_dot'),
        ];
    }
}
