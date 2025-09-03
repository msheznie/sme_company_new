<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateContractRequest extends FormRequest
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

        $rules =
        [
            'companySystemId' => 'required|string',
            'contractCategoryId' => 'required|integer',
            'contractUuid' => 'required',
        ];

       if ($this->input('contractCategoryId') == 2 || $this->input('contractCategoryId') == 5)
        {
             $rules = [
                 'counterParty' => 'required',
                 'title' => 'required',
                 'contractType' => 'required',
                 'contractOwner' => 'required',
                 'contractAmount' => 'required'
             ];
        }


        if ($this->input('contractCategoryId') == 4)
        {
            $rules =
                [
                    'revisedEndDate'=> 'required',
                    'reason'=> 'required|string'
                ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'companySystemId.required' =>  trans('common.company_id_is_required'),
            'contractCategoryId.required' => trans('common.category_id_is_different'),
            'contractUuid.required' => trans('common.contract_uuid_is_required'),
            'revisedEndDate.required' => trans('common.revised_end_date_is_required'),
            'reason.required' => trans('common.reason_is_required'),
        ];
    }
}
