<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
            'logo' => 'required_without:id|mimes:jpg,jpeg,png',
            'name' => 'required|string|max:100',
            'mobile' => 'required|max:100',
            'email' => 'sometimes|nullable|email',
            'category_id' => 'required|exists:main_categories,id',
            'address' => 'required|string|max:300'
        ];
    }
    public function messages()
    {
        return [
            'required' => 'هذا الحقل مطلوب' ,
            'max' => 'هذا الحقل طويل ' ,
            'category_id.exists' => 'هذا الفسم غير موجود' ,
            'email.email' => 'ضيغة البريد الالكتروني غير صحيحة' ,
            'address.string' => ' بل العنوان لابد ان يكون حروف',
            'required_without' => 'اللوجو مطلوب'
        ];
    }
}
