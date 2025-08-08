<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\SearchRequest;
use App\Http\Requests\Admin\Customer\StoreRequest;
use App\Http\Requests\Admin\Customer\UpdateRequest;
use App\Models\MstCustomer;
use App\Imports\CustomersImport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.customers.index');
    }
    public function CustomerList(SearchRequest $request)
    {
        $query = MstCustomer::select([
            'id',
            'customer_name',
            'email',
            'tel_num',
            'address',
            'is_active'
        ])
            ->orderBy('created_at', 'desc');
        foreach (['customer_name', 'email'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, 'like', '%' . $request->$field . '%');
            }
        }
        if ($request->filled('address')) {
            $keywords = explode(' ', $request->address);
            foreach ($keywords as $word) {
                $query->where('address', 'like', '%' . $word . '%');
            }
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('is_active', function ($customer) {
                return $customer->is_active
                    ? '<span class="badge badge-success">Hoạt động</span>'
                    : '<span class="badge badge-secondary">Không hoạt động</span>';
            })
            ->addColumn('action', function ($customer) {
                $deleteUrl = route('admin.customers.destroy', $customer->id);
                $editBtn =
                    '<button 
                        class="btn btn-sm btn-warning btn-edit mx-1"
                        data-id="' . $customer->id . '"
                        data-name="' . e($customer->customer_name) . '"
                        data-email="' . e($customer->email) . '"
                        data-phone="' . e($customer->tel_num) . '"
                        data-address="' . e($customer->address) . '"
                        data-status="' . $customer->is_active . '">
                    Sửa 
                    </button>';
                $deleteBtn =
                    '<button 
                        class="btn btn-sm btn-danger btn-delete mx-1"
                        data-id="' . $customer->id . '"
                        data-name="' . e($customer->customer_name) . '"
                        data-url="' . $deleteUrl . '">
                        Xóa
                    </button>';
                return '<div class="d-flex justify-content-center">' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }
    public function export(Request $request)
    {
        $fileName = 'export_customers_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        return (new CustomersExport($request))
            ->download($fileName);
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ], [
            'file.required' => 'Vui lòng chọn file để nhập.',
            'file.file' => 'Tệp tải lên không hợp lệ.',
            'file.mimes' => 'Chỉ cho phép file Excel có định dạng .xlsx hoặc .xls.',
        ]);

        $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.' . $request->file('file')->getClientOriginalExtension();
        $request->file('file')->move(dirname($tempPath), basename($tempPath));

        $import = new CustomersImport;
        Excel::import($import, $tempPath);

        @unlink($tempPath);

        if ($import->failures()->isNotEmpty()) {
            $errors = [];
            foreach ($import->failures() as $failure) {
                $errors[] = 'Dòng ' . $failure->row() . ', cột <b>' . $failure->attribute() . '</b>: ' . implode(', ', $failure->errors());
            }

            return back()->with('import_failures', $errors);
        }

        return back()->with('import_success', 'Import khách hàng thành công!');
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $customer = MstCustomer::create($data);
            DB::commit();
            return response()->json([
                'message' => 'Thêm khách hàng thành công',
                'customer' => $customer
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('message', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->with('alert-type', 'error');
        }
    }
    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $customer = MstCustomer::findOrFail($id);

            $customer->update($request->only([
                'customer_name',
                'email',
                'address',
                'tel_num',
                'is_active'
            ]));

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Cập nhật thông tin thành công!',
                ]);
            }

            return redirect()->route('admin.customers.index')
                ->with('message', 'Cập nhật khách hàng thành công!')
                ->with('alert-type', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi cập nhật khách hàng: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cập nhật thất bại, vui lòng thử lại.',
                ], 500);
            }

            return redirect()->back()
                ->with('message', 'Đã có lỗi xảy ra: ' . $e->getMessage())
                ->with('alert-type', 'error');
        }
    }
    public function destroy($id)
    {
        $customer = MstCustomer::findOrFail($id);
        $customer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa khách hàng thành công!'
        ]);
    }
}
