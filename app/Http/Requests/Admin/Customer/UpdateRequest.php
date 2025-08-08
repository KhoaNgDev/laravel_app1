<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'customer_name' => 'required|string|min:10|max:100',
            'email' => 'required|email|unique:mst_customers,email,' . $this->route('id'),
            'tel_num' => 'required|regex:/^0\d{9}$/',
            'address' => 'nullable|string',
            'is_active' => 'required|in:0,1',
        ];
    }
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Tên khách hàng không được để trống.',
            'customer_name.string' => 'Tên khách hàng phải là chuỗi.',
            'customer_name.min' => 'Tên khách hàng phải có ít nhất 10 ký tự.',
            'customer_name.max' => 'Tên khách hàng không được quá 100 ký tự.',

            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',

            'tel_num.required' => 'Số điện thoại không được để trống.',
            'tel_num.regex' => 'Số điện thoại phải là 10 chữ số và bắt đầu bằng số 0.',

            'address.string' => 'Địa chỉ phải là chuỗi văn bản.',

            'is_active.required' => 'Trạng thái không được để trống.',
            'is_active.in' => 'Trạng thái chỉ được là Hoạt động (1) hoặc Không hoạt động (0).',
        ];
    }

}
