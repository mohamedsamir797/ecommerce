<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MainCategoryRequest extends FormRequest
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
            'photo' => 'required|mimes:jpg,jpeg,png',
            'category' =>'required|array|min:1',
            'category.*.name' =>'required',
            'category.*.translation_lang' =>'required',
            'category.*.active' =>'required',
        ];
    }
    public function messages()
    {
        return [
            'photo.required' => 'هذه الصورة مطلوبة',
            'category.required' => 'هذا القسم مطلوب',
            'category.*.name.required' => 'هذا الاسم مطلوب',
            'category.*.translation_lang.required' => 'اختصار اللغة مطلوب',
            'category.*.active.required' => 'هذه الحالة مطلوبة',

        ];
    }
}
