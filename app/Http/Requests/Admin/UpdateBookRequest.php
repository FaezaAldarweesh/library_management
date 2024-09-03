<?php

namespace App\Http\Requests\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'unique:books,id|regex:/^[\p{L}\s]+$/u|min:3|max:50',
            'author' => 'regex:/^[\p{L}\s]+$/u|min:3|max:50',
            'category_id' => 'integer|exists:categories,id',
            'description' => 'string|min:10|max:150',
            'published_at' => 'date',
            'status' => 'nullable|in:available,unavailable',
        ];
    }
    //===========================================================================================================================
    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'status' => 'error 422',
            'message' => 'فشل التحقق يرجى التأكد من المدخلات',
            'errors' => $validator->errors(),
        ]));
    }
    //===========================================================================================================================
    protected function passedValidation()
    {
        //تسجيل وقت إضافي
        Log::info('تمت عملية التحقق بنجاح في ' . now());

    }
    //===========================================================================================================================
    public function attributes(): array
    {
        return [
            'title' => 'اسم الكتاب',
            'author' => 'اسم المؤلف',
            'category_id' => 'اسم التصنيف',
            'description' => 'وصف الكتاب',
            'published_at' => 'تاريخ النشر',
        ];
    }
    //===========================================================================================================================

    public function messages(): array
    {
        return [
            'unique' => ':attribute  موجود سابقاً , يجب أن يكون :attribute غير مكرر',
            'regex' => 'يجب أن يحوي  :attribute على أحرف فقط',
            'max' => 'الحد الأقصى لطول  :attribute هو 10 حرف',
            'min' => 'الحد الأدنى لطول :attribute على الأقل هو 3 حرف',
            'integer' => ' يجب أن يكون الحقل :attribute من نمط intger لأننا نخزن id الحقل',
            'exists' => 'اسم:attribute غير موجود , يجب أن يكون :attribute موجود ضمن التصنيفات المخزنة سابقا',
            'date' => 'يجب أن يكون :attribute تاريخ',
            'in' => 'يأخذ الحقل :attribute فقط قيمتين إما (available أو unavailable)',

            'description.min' => 'الحد الأدنى لطول الوصف هو 10 حرف',
            'description.max' => 'الحد الأقصى لطول الوصف هو 150 حرف',
        ];
    }
}
