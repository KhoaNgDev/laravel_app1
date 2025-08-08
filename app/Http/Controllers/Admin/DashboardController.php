<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MstCustomer;
use App\Models\MstProduct;
use App\Models\User;
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function AdminDashboard()
    {
        $totalProducts = MstProduct::count();
        $totalCustomers = MstCustomer::count();
        $totalUsers = User::count();

        $newUsers = User::where('created_at', '>=', Carbon::now()
            ->subDays(0.2))
            ->count();

        return view('admin.dashboard.index', [
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'newUsers' => $newUsers,
            'totalUsers' => $totalUsers
        ]);
    }
}
