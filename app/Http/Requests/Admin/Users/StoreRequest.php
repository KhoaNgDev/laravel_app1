<?php

namespace App\Http\Requests\Admin\Users;

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
    public function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:100', 'unique:users,name'],
            'email' => [
                'required',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/',
                'unique:users,email'
            ],
            'phone' => ['required', 'unique:users,phone'],
            'password' => [
                'required',
                'min:6',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'confirmed'
            ],
            'group_role' => ['required', 'in:Admin,Reviewer,Editor']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên người sử dụng.',
            'name.min' => 'Tên phải có ít nhất :min ký tự.',
            'name.max' => 'Tên người dùng không được vượt quá :max ký tự.',
            'name.unique' => 'Tên người dùng đã được sử dụng.',

            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.regex' => 'Email phải có định dạng hợp lệ và là địa chỉ @gmail.com.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',

            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.unique' => 'Số điện thoại đã được sử dụng.',

            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ thường, 1 chữ hoa và 1 số.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',

            'group_role.required' => 'Vui lòng chọn nhóm người dùng.',
            'group_role.in' => 'Giá trị nhóm người dùng không hợp lệ.',
        ];
    }



}
