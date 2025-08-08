<?php

namespace App\Http\Requests\Admin\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $userId = $this->route('id');
        $rules = [
            'name' => 'required|string|min:3|max:100|unique:users,name,' . $userId,
            'email' => 'required|email|unique:users,email,' . $userId,
            'phone' => 'required|unique:users,phone,' . $userId,
            'group_role' => ['required', 'in:Admin,Reviewer,Editor'],
            'is_active' => ['in:active,inactive'],
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ];

        if ($this->filled('password')) {
            $rules['password'] = [
                'required',
                'min:6',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'confirmed',
            ];
        }

        return $rules;
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên người dùng.',
            'name.string' => 'Tên người dùng phải là chuỗi ký tự.',
            'name.min' => 'Tên người dùng phải có ít nhất :min ký tự.',
            'name.max' => 'Tên người dùng phải không được quá :max ký tự.',
            'name.unique' => 'Tên người dùng đã bị trùng',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'email.unique' => 'Email đã được sử dụng.',

            'group_role.required' => 'Vui lòng chọn vai trò.',
            'group_role.in' => 'Vai trò không hợp lệ. Chỉ chấp nhận: Admin, Reviewer, Editor.',

            'is_active.in' => 'Trạng thái không hợp lệ. Chỉ chấp nhận: active hoặc inactive.',

            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.regex' => 'Mật khẩu phải bao gồm ít nhất một chữ hoa, một chữ thường và một chữ số.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ];
    }


}
