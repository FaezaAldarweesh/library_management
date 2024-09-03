<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => 'min:1|max:5',
            'review' => 'string|nullable',
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
                'rating' => 'التقييم',
                'review' => 'المراجعة',
            ];
        }
        //===========================================================================================================================
    
        public function messages(): array
        {
            return [
                'max' => 'الحد الأقصى لطول  :attribute هو 5 حرف',
                'min' => 'الحد الأدنى لطول :attribute على الأقل هو 1 حرف',
            ];
        }
}
