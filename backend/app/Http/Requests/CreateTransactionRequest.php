<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\HttpResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class CreateTransactionRequest extends FormRequest
{

    use HttpResponses;

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
            "value" => 'required|numeric|min:0',
            'clientId' => 'required',
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'value.required' => 'The value attribute is required.',
            'value.numeric' => 'The value must be a number',
            'value.min' => 'Transaction amount must be greater than 0',
            'clientId.required' => 'The clientId attribute is required.',
        ];
    }

    protected function failedValidation(Validator $validator){ 
        $errors = $validator->errors()->all(); 
        throw new HttpResponseException($this->error("Validation failed.", 422, $errors));
    }
}