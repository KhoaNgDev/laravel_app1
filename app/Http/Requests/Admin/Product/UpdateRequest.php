<?php

namespace App\Http\Requests\Admin\Product;

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
        $productId = $this->route('product_id');

        return [
            'product_name' => [
                'required',
                'string',
                'min:6',
                'max:50',
                Rule::unique('mst_products', 'product_name')->ignore($productId, 'product_id'),
            ],
            'product_price' => [
                'required',
                'numeric',
                'min:0',
                'max:100000000000000'

            ],
            'product_description' => [
                'nullable',
                'string',
                'max:200'
            ],
            'is_sales' => [
                'required',
                'in:in_storage,stop_sales,out_of_stock',
            ],
            'product_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
                'dimensions:max_width=1024,max_height=1024',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'product_name.required' => 'Vui lòng nhập tên sản phẩm.',
            'product_name.min' => 'Tên phải lớn hơn 5 ký tự.',
            'product_name.unique' => 'Tên sản phẩm đã tồn tại, vui lòng chọn tên khác.',
            'product_name.max' => 'Tên sản phẩm không được quá 50 ký tự.',

            'product_price.required' => 'Giá bán không được để trống.',
            'product_price.numeric' => 'Giá bán chỉ được nhập số.',
            'product_price.min' => 'Giá bán không được nhỏ hơn 0.',
            'product_price.max' => 'Giá bán không hợp lệ.',
            'product_description.string' => 'Mô tả phải là chuỗi văn bản.',
            'product_description.max' => 'Mô tả không được quá 200 ký tự.',

            'is_sales.required' => 'Trạng thái không được để trống.',
            'is_sales.in' => 'Trạng thái không hợp lệ.',


            'product_image.image' => 'Tập tin phải là hình ảnh.',
            'product_image.mimes' => 'Chỉ cho phép hình ảnh jpg, jpeg, png.',
            'product_image.max' => 'Dung lượng ảnh không quá 2MB.',
            'product_image.dimensions' => 'Kích thước hình ảnh không được quá 1024px.',
        ];
    }
}
