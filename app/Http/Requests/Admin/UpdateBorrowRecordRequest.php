<?php

namespace App\Http\Requests\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBorrowRecordRequest extends FormRequest
{
  /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    //===========================================================================================================================
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $DateBorrow = Carbon::today()->toDateTimeString();
        
        return [
            'book_id' => 'nullable|integer|exists:books,id',
            'user_id' => 'nullable|integer|exists:users,id',
            'borrow_at' => 'nullable|date|date_equals:' . $DateBorrow,
            'due_at' => 'nullable|date|after_or_equal:borrow_at',
            'status' => 'in:It has not been borrowed yet,the book has been took',
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
            'book_id' => 'اسم الكتاب',
            'user_id' => 'اسم الكتاب',
            'borrow_at' => 'تاريخ الاستعارة',
            'due_at' => 'تاريخ الإعادة',
            'status' => 'حالة الاستعارة',
        ];
    }
    //===========================================================================================================================

    public function messages(): array
    {
        return [
            'integer' => ' يجب أن يكون الحقل :attribute من نمط intger لأننا نخزن id الحقل',
            'exists' => ':attribute غير موجود , يجب أن يكون :attribute موجود ضمن التصنيفات المخزنة سابقا',
            'date' => 'يجب أن يكون :attribute تاريخ',
            'date_equals' => 'يجب أن يكون :attribute بتاريخ اليوم حصراً',
            'after_or_equal' => 'يجب أن يكون :attribute بتاريخ بعد تاريخ الاستعارة أو في نفس اليوم',
            'in' => 'يأخذ الحقل :attribute فقط القيم إما (It has not been borrowed yet أو the book has been took)',
        ];
    }
}
