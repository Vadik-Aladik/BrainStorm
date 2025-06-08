<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Поле пустое',
            'email.string' => 'Поле должно содержать строку',
            'email.email' => 'Вы не ввели электронную почту',
            'email.exists' => 'Данной почты не существует',
            'password.required' => 'Поле пустое',
            'password.string' => 'Поле должно содержать строку',
            'password.min' => 'Минимальное количество символов - 8',
        ];
    }
}
