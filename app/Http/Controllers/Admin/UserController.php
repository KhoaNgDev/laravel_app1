<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\SearchRequest;
use App\Http\Requests\Admin\Users\StoreRequest;
use App\Http\Requests\Admin\Users\UpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }
    public function UserList(SearchRequest $request)
    {
        $query = User::select([
            'id',
            'name',
            'email',
            'phone',
            'is_active',
            'group_role',
            'photo'
        ])
            ->where('id', '!=', 1)
            ->where('is_delete', 0)
            ->orderBy('created_at', 'desc');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', $request->email);
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        if ($request->filled('group_role')) {
            $query->where('group_role', $request->group_role);
        }
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('name', function ($user) {
                $imageUrl = $user->photo
                    ? asset('uploads/user_images/' . $user->photo)
                    : asset('assets/img/avatar/avatar-1.png');

                return '<span class="user-hover" data-img="' . $imageUrl . '">' . e($user->name) . '</span>';
            })
            ->editColumn('is_active', function ($user) {
                return match ($user->is_active) {
                    'active' => '<span class="badge badge-primary">Đang hoạt động</span>',
                    'inactive' => '<span class="badge badge-danger">Ngừng hoạt động</span>',
                    default => '<span class="badge badge-warning">Chưa xác định</span>',
                };
            })
            ->editColumn('group_role', function ($user) {
                return match ($user->group_role) {
                    'Admin' => '<span class="badge badge-success">Quản Trị Viên</span>',
                    'Reviewer' => '<span class="badge badge-warning">Người Đánh Giá</span>',
                    'Editor' => '<span class="badge badge-secondary">Người Chỉnh Sửa</span>',
                    default => '<span class="badge badge-danger">Chưa xác định</span>',
                };
            })
            ->addColumn('action', function ($user) {
                $editBtn =
                    '<button 
                    class="btn btn-sm btn-primary btn-user-edit" 
                    data-id="' . $user->id . '"
                    data-name="' . e($user->name) . '"
                    data-email="' . e($user->email) . '"
                    data-phone="' . e($user->phone) . '"
                    data-is_active="' . $user->is_active . '"
                    data-group_role="' . $user->group_role . '"
                    data-photo="' . asset('uploads/user_images/' . $user->photo) . '">
                <i class="far fa-edit"></i>
                </button>';


                $toggleBtn =
                    '<button 
                    class="btn btn-sm ' . ($user->is_active === 'active' ? 'btn-warning' : 'btn-success') . ' btn-toggle-status"
                    data-id="' . $user->id . '"
                    data-name="' . e($user->name) . '"
                    data-status="' . $user->is_active . '"
                    data-url="' . route('admin.users.toggle', $user->id) . '">
                <i class="' . ($user->is_active === 'active' ? 'fas fa-lock' : 'fas fa-unlock') . '"></i>
                </button>';

                $deleteBtn =
                    '<a     
                        href="#"
                        class="btn btn-icon btn-danger btn-delete-user"
                        data-id="' . $user->id . '"
                        data-name="' . e($user->name) . '"
                        data-url="' . route('admin.users.softDelete', $user->id) . '">
                        <i class="fas fa-times"></i>
                    </a>';

                return $editBtn . ' ' . $toggleBtn . ' ' . $deleteBtn;
            })
            ->rawColumns(['name', 'is_active', 'group_role', 'action'])
            ->make(true);
    }
    public function store(StoreRequest $request)
    {
        $data = $request
            ->only([
                'id',
                'name',
                'email',
                'photo',
                'phone',
                'address',
                'password',
                'group_role',
                'is_active'
            ]);

        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/user_images'), $filename);
            $data['photo'] = $filename;
        }

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('message', 'Thêm người dùng thành công!')
            ->with('alert-type', 'success');
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'group_role' => $user->group_role,
            'is_active' => $user->is_active,
            'photo' => $user->photo
                ? asset('uploads/user_images/' . $user->photo)
                : asset('uploads/no_image.jpg'),
        ]);
    }
    public function update(UpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->only(['name', 'email', 'phone', 'photo', 'address', 'group_role', 'is_active']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        if ($request->hasFile('photo')) {
            if ($user->photo && file_exists(public_path('uploads/user_images/' . $user->photo))) {
                unlink(public_path('uploads/user_images/' . $user->photo));
            }
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/user_images'), $filename);
            $data['photo'] = $filename;
        }
        $user->update($data);
        return redirect()->route('admin.users.index')
            ->with('message', 'Chỉnh sửa người dùng thành công!')
            ->with('alert-type', 'success');
    }
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        $user->is_active = $user->is_active === 'active' ? 'inactive' : 'active';
        $user->save();

        return response()->json(['message' => 'Cập nhật trạng thái thành công!']);
    }
    public function softDelete($id)
    {
        $user = User::findOrFail($id);
        $user->is_delete = 1;
        $user->save();
        return response()->json(['message' => 'Xoá người dùng thành công!']);
    }

}
