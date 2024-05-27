<?php

namespace App\Http\Requests\API;

use App\Models\ErpApprovalLevel;
use InfyOm\Generator\Request\APIRequest;

class UpdateErpApprovalLevelAPIRequest extends APIRequest
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
        $rules = ErpApprovalLevel::$rules;
        
        return $rules;
    }
}
