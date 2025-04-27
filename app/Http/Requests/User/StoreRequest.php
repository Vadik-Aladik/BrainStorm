<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
            'password_confirm' => 'required|string|same:password',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле пустое',
            'name.string' => 'Поле должно содержать строку',
            'email.required' => 'Поле пустое',
            'email.string' => 'Поле должно содержать строку',
            'email.email' => 'Вы не ввели электронную почту',
            'email.unique' => 'Данная почта уже существует',
            'password.required' => 'Поле пустое',
            'password.string' => 'Поле должно содержать строку',
            'password.min' => 'Минимальное количество символов - 8',
            'password_confirm.required' => 'Поле пустое',
            'password_confirm.string' => 'Поле должно содержать строку',
            'password_confirm.same' => 'Пароли не совпадают',
        ];
    }
}
