<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\HttpResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UpdateClientRequest extends FormRequest
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
            'name' => 'max:255',
            'email' => 'email|max:255',
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
            'name.max' => 'The name attribute cannot be longer than 255 characters.',
            'email.email' => 'Invalid email.',
            'email.max' => 'The email attribute cannot be longer than 255 characters.',
        ];
    }

    protected function failedValidation(Validator $validator){ 
        $errors = $validator->errors()->all(); 
        throw new HttpResponseException($this->error("Validation failed.", 422, $errors));
    }
}