<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateContractHistoryAttachmentRequest extends FormRequest
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
            'contractCategory' => 'required|integer',
            'uuid' => 'required',
            'file' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'selectedCompanyID.required' =>  trans('common.company_id_is_required'),
            'contractCategory.required' => trans('common.category_id_is_different'),
            'uuid.required' => trans('common.contract_uuid_is_required'),
            'file.required' => trans('common.file_is_required')
        ];
    }
}
