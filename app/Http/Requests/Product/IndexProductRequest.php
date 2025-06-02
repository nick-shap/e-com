<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class IndexProductRequest extends FormRequest
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
            'per_page' => 'sometimes|integer|min:1',
            'sort_by' => 'sometimes|string|in:created_at,price,stock_quantity',
            'sort_order' => 'sometimes|string|in:asc,desc',
        ];
    }

    public function messages(): array
    {
        return [
            'sort_by.string' => 'Поле "Сортировка" должно быть строкой.',
            'sort_order.string' => 'Поле "Порядок сортировки" должно быть строкой.',
            'per_page.integer' => 'Поле "per_page" должно быть целым числом.',
            'per_page.min' => 'Поле "per_page" не может быть менее :min.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
