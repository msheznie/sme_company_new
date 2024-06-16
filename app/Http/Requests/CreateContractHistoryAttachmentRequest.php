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
            'attachmentName' => 'required',
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'selectedCompanyID.required' =>  'Company id is required',
            'contractCategory.required' => 'Category id is different',
            'uuid.required' => 'contract uuid is required',
            'file.required' => 'File is required',
            'attachmentName.required' => 'Attachment Name is required',
            'description.required' => 'Attachment Name is required',
        ];
    }
}
