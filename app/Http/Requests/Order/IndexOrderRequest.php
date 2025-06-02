<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderStatusEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class IndexOrderRequest extends FormRequest
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
            'sort_by' => 'sometimes|string|in:created_at,total_price',
            'sort_order' => 'sometimes|string|in:asc,desc',
            'status' => [
                'sometimes',
                'string',
                Rule::enum(OrderStatusEnum::class),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'sort_by.string' => 'Поле "Сортировка" должно быть строкой.',
            'sort_order.string' => 'Поле "Порядок сортировки" должно быть строкой.',
            'per_page.integer' => 'Поле "per_page" должно быть целым числом.',
            'per_page.min' => 'Поле "per_page" не может быть менее :min.',
            'status.string' => 'Поле "status" должно быть строкой',
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
