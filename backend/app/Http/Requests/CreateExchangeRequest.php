<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\HttpResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class CreateExchangeRequest extends FormRequest
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
            "clientId" => 'required',
            'rewardId' => 'required',
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
            'rewardId.required' => 'The rewardId attribute is required.',
            'clientId.required' => 'The clientId attribute is required.',
        ];
    }

    protected function failedValidation(Validator $validator){ 
        $errors = $validator->errors()->all(); 
        throw new HttpResponseException($this->error("Validation failed.", 422, $errors));
    }
}