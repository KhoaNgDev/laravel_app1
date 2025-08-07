<?php

namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:50'],
            'is_active' => ['nullable', 'in:active,inactive'],
            'group_role' => ['nullable', 'in:Admin,Reviewer,Editor'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Tên tối đa 50 ký tự.',
            'email.max' => 'Email tối đa 50 ký tự.',
            'email.email' => 'Email không hợp lệ.',
            'is_active.in' => 'Trạng thái không hợp lệ.',
            'group_role.in' => 'Vai trò không hợp lệ.',
        ];
    }
}
