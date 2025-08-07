<?php

namespace App\Http\Requests\Admin\Customer;

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
            'customer_name' => ['required', 'string', 'min:6'],
            'email' => ['required', 'email', 'unique:mst_customers,email'],
            'tel_num' => [
                'nullable',
                'regex:/^(0|\+84)[0-9]{9}$/',
                'unique:mst_customers,tel_num'
            ],
            'address' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Vui lòng nhập tên khách hàng',
            'customer_name.min' => 'Tên khách hàng phải lớn hơn 5 ký tự',

            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được đăng ký.',

            'tel_num.required' => 'Điện thoại không được để trống',
            'tel_num.regex' => 'Nhập không đúng định dạng điện thoại.',
            'tel_num.unique' => 'Số điện thoại đã trùng.',
            'address.required' => 'Địa chỉ không được để trống.',
        ];
    }
}
