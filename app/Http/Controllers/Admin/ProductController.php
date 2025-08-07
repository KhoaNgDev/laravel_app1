<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Http\Requests\Admin\Product\SearchRequest;
use App\Http\Requests\Admin\Product\ProductStoreRequest;
use App\Models\MstProduct;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.products.index');
    }
    public function ProductList(SearchRequest $request)
    {
        $query = MstProduct::select([
            'product_id',
            'product_name',
            'product_price',
            'is_sales',
            'product_image',
        ]);

        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', 'like', '%' . $request->product_id . '%');
        }
        if ($request->filled('is_sales')) {
            $query->where('is_sales', $request->is_sales);
        }
        if ($request->filled('price_min')) {
            $priceMin = (float) str_replace('.', '', $request->price_min);
            $query->where('product_price', '>=', $priceMin);
        }
        if ($request->filled('price_max')) {
            $priceMax = (float) str_replace('.', '', $request->price_max);
            $query->where('product_price', '<=', $priceMax);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('product_name', function ($product) {
                $imageUrl = $product->product_image
                    ? asset('uploads/products/' . $product->product_image)
                    : asset('uploads/no_image.jpg');

                return '<span class="product-hover" data-img="' . $imageUrl . '">' . e($product->product_name) . '</span>';
            })
            ->editColumn('product_price', function ($product) {
                return (int) $product->product_price;
            })
            ->editColumn('is_sales', function ($p) {
                return match ($p->is_sales) {
                    'in_storage' => '<span class="badge badge-success">Đang bán</span>',
                    'stop_sales' => '<span class="badge badge-warning">Ngừng bán</span>',
                    default => '<span class="badge badge-danger">Hết hàng</span>',
                };
            })
            ->addColumn('action', function ($product) {

                $deleteUrl = route('admin.products.destroy', $product->product_id);

                $editBtn =
                    '<button 
                        class="btn btn-sm btn-primary btn-edit" 
                        data-id="' . $product->product_id . '">Sửa
                    </button>';

                $deleteBtn =
                    '<button 
                        class="btn btn-sm btn-danger btn-delete"
                        data-id="' . $product->product_id . '"
                        data-name="' . e($product->product_name) . '"
                        data-url="' . $deleteUrl . '">
                        Xóa
                    </button>';

                return $editBtn . ' ' . $deleteBtn;
            })
            ->rawColumns(['product_name', 'is_sales', 'action'])
            ->make(true);
    }
    public function store(ProductStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $firstChar = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $request->product_name), 0, 1));
            $prefix = $firstChar ?: 'P';

            $nextNumber = now()->format('YmdHis') . rand(10, 99);
            $data = $request->only(['product_name', 'product_price', 'product_description', 'is_sales']);
            $data['product_id'] = $prefix . $nextNumber;

            if ($request->hasFile('product_image')) {
                $imagePath = $this->uploadImage($request->file('product_image'));
                if (!$imagePath) {
                    throw new Exception('Tải ảnh thất bại.');
                }
                $data['product_image'] = $imagePath;
            }

            MstProduct::create($data);
            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('message', 'Thêm sản phẩm thành công!')
                ->with('alert-type', 'success');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('message', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->with('alert-type', 'error');
        }
    }

    public function edit($id)
    {
        $product = MstProduct::findOrFail($id);
        return response()->json([
            'product_name' => $product->product_name,
            'product_price' => $product->product_price,
            'product_description' => $product->product_description,
            'is_sales' => $product->is_sales,
            'product_image_url' => $product->product_image
                ? asset('uploads/products/' . $product->product_image)
                : asset('uploads/no_image.jpg'),
        ]);
    }
    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $product = MstProduct::findOrFail($id);
            $data = $request->only([
                'product_name',
                'product_price',
                'product_description',
                'is_sales'
            ]);

            if ($request->input('remove_image') == 1 && $product->product_image) {
                $oldPath = public_path('uploads/products/' . $product->product_image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
                $data['product_image'] = null;
            }

            if ($request->hasFile('product_image')) {
                if ($product->product_image && File::exists(public_path('uploads/products/' . $product->product_image))) {
                    File::delete(public_path('uploads/products/' . $product->product_image));
                }

                $data['product_image'] = $this->uploadImage($request->file('product_image'));
            }

            $product->update($data);

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('message', 'Cập nhập sản phẩm thành công!')
                ->with('alert-type', 'success');

        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return redirect()->back()
                ->with('message', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->with('alert-type', 'error');
        }
    }

    public function destroy($id)
    {
        $product = MstProduct::findOrFail($id);
        if ($product->product_image && File::exists(public_path('uploads/products/' . $product->product_image))) {
            File::delete(public_path('uploads/products/' . $product->product_image));
        }
        $product->delete();
        return response()->json(['status' => 'success', 'message' => 'Xóa sản phẩm thành công!']);
    }
    private function uploadImage($file)
    {
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/products'), $filename);
        return $filename;
    }
}
