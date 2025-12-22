<?php

namespace App\Http\Requests;

use App\Models\ERPLanguageMaster;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Language;

class LanguageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'languageID' => [
                'required',
                Rule::exists(ERPLanguageMaster::class, 'languageID')->where(fn($query) => $query->where('isActive', 1)),
            ],
        ];
    }

    public function messages()
    {
        return [
            'languageID.required' => trans('common.language_id_is_required'),
            'languageID.exists' => trans('common.selected_language_does_not_exist_or_is_not_active'),
        ];
    }
}
