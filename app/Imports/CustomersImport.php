<?php
namespace App\Imports;

use App\Models\MstCustomer;




use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class CustomersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithBatchInserts
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new MstCustomer([
            'customer_name' => $row['ten_khach_hang'],
            'email' => $row['email'],
            'tel_num' => $row['so_dien_thoai'],
            'address' => $row['dia_chi'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.ten_khach_hang' => 'required|string|min:3|max:200',
            '*.email' => 'required|email|unique:mst_customers,email',
            '*.so_dien_thoai' => 'required|regex:/^0[0-9]{9}$/',
            '*.dia_chi' => 'nullable|string|max:200|',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.ten_khach_hang.required' => 'Tên khách hàng không được để trống.',
            '*.ten_khach_hang.string' => 'Tên khách hàng phải là chuỗi ký tự.',
            '*.ten_khach_hang.min' => 'Tên khách hàng phải có ít nhất :min ký tự.',
            '*.ten_khach_hang.max' => 'Tên khách hàng không được vượt quá :max ký tự.',

            '*.email.required' => 'Email không được để trống.',
            '*.email.email' => 'Email không đúng định dạng.',
            '*.email.unique' => 'Email đã tồn tại trong hệ thống.',

            '*.so_dien_thoai.required' => 'Số điện thoại không được để trống.',
            '*.so_dien_thoai.regex' => 'Số điện thoại phải gồm 10 chữ số và bắt đầu bằng số 0.',

            '*.dia_chi.string' => 'Địa chỉ phải là chuỗi ký tự.',
            '*.dia_chi.max' => 'Địa chỉ không được vượt quá :max ký tự.',
        ];
    }


    public function batchSize(): int
    {
        return 100;
    }
}
