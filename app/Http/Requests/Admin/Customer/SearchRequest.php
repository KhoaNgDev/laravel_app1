<?php

namespace App\Http\Requests\Admin\Customer;

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
    public function rules(): array
    {
        return [
            'customer_name' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:50'],
            'address' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.max' => 'Tên tối đa 50 ký tự.',
            'email.max' => 'Email tối đa 50 ký tự.',
            'email.email' => 'Email không hợp lệ.',
            'address.max' => 'Địa chỉ không hợp lệ, dài quá 100 kí tự'
        ];
    }
}
