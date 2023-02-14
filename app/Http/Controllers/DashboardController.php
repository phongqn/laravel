<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'dashboard']);
            return $next($request);
        });
    }
    function show()
    {
        // if (!session('login_success')) {
        //     Toastr::success('Thông báo', 'Đăng nhập thành công');
        //     session(['login_success' => true]);
        // }
        return view('admin.dashboard');
    }
}
