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
            'companySystemId.required' =>  'Company id is required',
            'contractCategoryId.required' => 'Category id is different',
            'contractUuid.required' => 'contract uuid is required',
            'revisedEndDate.required' => 'Revised end date is required',
            'reason.required' => 'Reason is required',
        ];
    }
}
