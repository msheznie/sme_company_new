<?php

namespace App\Http\Requests\API;

use App\Models\DocumentMaster;
use InfyOm\Generator\Request\APIRequest;

class CreateDocumentMasterAPIRequest extends APIRequest
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
            'documentType' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'selectedCompanyID.required' => trans('common.company_id_is_required_dot'),
            'documentType.required' => trans('common.document_type_is_required'),
        ];
    }
}
