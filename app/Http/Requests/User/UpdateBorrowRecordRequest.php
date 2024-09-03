<?php

namespace App\Http\Requests\User;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
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
            'book_id' => 'integer|exists:books,id',
            'borrow_at' => 'date|date_equals:' . $DateBorrow,
            'due_at' => 'date|after_or_equal:borrow_at',
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
    public function attributes(): array
    {
        return [
            'book_id' => 'اسم الكتاب',
            'borrow_at' => 'تاريخ الاستعارة',
            'due_at' => 'تاريخ الإعادة',
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
        ];
    }
}
