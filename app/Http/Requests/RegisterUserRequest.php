<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 允许所有用户访问此请求
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])/',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errorMessage = reset($errors)[0];

            throw new HttpResponseException(
                response()->json(['message' => $errorMessage], 422)
            );
        }
    }
}
