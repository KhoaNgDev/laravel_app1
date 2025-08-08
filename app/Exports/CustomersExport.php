<?php
namespace App\Exports;

use App\Models\MstCustomer;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromQuery, WithHeadings, WithMapping, Responsable
{
    use Exportable;
    public string $fileName = 'export_customers.xlsx';
    protected ?Request $request;
    public function __construct(?Request $request = null)
    {
        $this->request = $request;
    }
    public function query()
    {
        $query = MstCustomer::query()->select([
            'customer_name',
            'email',
            'tel_num',
            'address',
        ]);

        $filtered = false;

        if ($this->request) {
            if ($this->request->filled('customer_name')) {
                $filtered = true;
                $query->where('customer_name', 'like', '%' . $this->request->customer_name . '%');
            }

            if ($this->request->filled('email')) {
                $filtered = true;
                $query->where('email', 'like', '%' . $this->request->email . '%');
            }

            if ($this->request->filled('address')) {
                $filtered = true;
                foreach (explode(' ', $this->request->address) as $keyword) {
                    $query->where('address', 'like', '%' . $keyword . '%');
                }
            }

            if ($this->request->filled('is_active')) {
                $filtered = true;
                $query->where('is_active', $this->request->is_active);
            }
        }
        if (!$filtered) {
            $query->limit(20);
        }
        return $query;
    }
    public function headings(): array
    {
        return [
            'ten_khach_hang',
            'email',
            'so_dien_thoai',
            'dia_chi',
        ];
    }

    public function map($row): array
    {
        return [
            $row->customer_name,
            $row->email,
            $row->tel_num,
            $row->address,
        ];
    }
}
