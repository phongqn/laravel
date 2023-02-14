<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminAdsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'ads']);
            return $next($request);
        });
    }
    public function list(Request $request)
    {
        $status = $request->input('status');
        if ($status == 'pending') {
            $list_action = [
                'public' => 'Công khai'
            ];
            $ads = Ads::where('status', '1')->latest()->paginate(5);
        } else {
            $list_action = [
                'pending' => 'Tạm ẩn'
            ];
            $keyword = "";
            if ($request->input('q')) {
                $keyword = $request->input('q');
            }
            $ads = Ads::where('name', 'LIKE', "%{$keyword}%")->where('status', '0')->paginate(5);
        }
        $count['all'] = Ads::all()->count();
        $count['public'] = Ads::where('status', '0')->count();
        $count['pending'] = Ads::where('status', '1')->count();
        return view('admin/ads/list', compact('ads', 'list_action', 'count'));
    }
    public function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if ($list_check) {
            if (!empty($list_check)) {
                $action = $request->input('action');
                if ($action == 'public') {
                    Ads::whereIn('id', $list_check)->update(['status' => '0']);
                    Toastr::success('Thông báo', 'Chuyển ads công khai thành công');
                    return redirect('admin/ads/list');
                }
                if ($action == 'pending') {
                    Ads::whereIn('id', $list_check)->update(['status' => '1']);
                    Toastr::success('Thông báo', 'Chuyển ads thành tạm thời thành công');
                    return redirect('admin/ads/list');
                }
            }
        } else {
            Toastr::error('Thông báo', 'Bạn chưa chọn phần tử cần thao tác');
            return back();
        }
    }
    public function add()
    {
        return view('admin/ads/add');
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|unique:ads',
                'thumb' => 'required',
                'link' => 'required|',
                'status' => 'required'
            ],
            [
                'required' => ':attribute không được để trống',
                'unique' => ':attribute đã tồn tại trong hệ thống'
            ],
            [
                'name' => 'Tên ads',
                'thumb' =>  'Ảnh ads',
                'link' => 'Link ads',
                'status' => 'Trạng thái'
            ]
        );
        if ($request->hasFile('thumb')) {
            $file = $request->file('thumb');
            // $fileName = $file->getClientOriginalName();
            $fileName = Str::slug($request->input('name'));
            $file_ex = $file->getClientOriginalExtension();
            $file->move('public/admin/uploads/adss', $fileName . '.' . $file_ex);
            $thumbnail = 'public/admin/uploads/adss/' . $fileName . '.' . $file_ex;
        }
        // return $request->all();
        Ads::create([
            'name' => $request->input('name'),
            'link' => $request->input('link'),
            'thumb' => $thumbnail,
            'user_id' => Auth::id(),
            'status' => $request->input('status')
        ]);
        Toastr::success('Thông báo', 'Thêm ads thành công');
        return redirect('admin/ads/list');
    }
    public function edit($id)
    {
        $ads = Ads::find($id);
        return view('admin/ads/edit', compact('ads'));
    }
    public function update(Request $request, $id)
    {
        $ads = Ads::find($id);
        $old_thumb = $ads->thumb;
        $request->validate(
            [
                'name' => 'required',
                'link' => 'required',
                'status' => 'required'
            ],
            [
                'required' => ':attribute không được để trống',
                'unique' => ':attribute đã tồn tại trong hệ thống'
            ],
            [
                'name' => 'Tên ads',
                'link' => 'Link ads',
                'status' => 'Trạng thái'
            ]
        );
        if ($request->hasFile('thumb')) {
            $file = $request->file('thumb');
            Storage::delete(url($old_thumb));
            // $fileName = $file->getClientOriginalName();
            $fileName = Str::slug($request->input('name'));
            $file_ex = $file->getClientOriginalExtension();
            $file->move('public/admin/uploads/adss', $fileName . '.' . $file_ex);
            $thumbnail = 'public/admin/uploads/adss/' . $fileName . '.' . $file_ex;
        } else {
            $thumbnail = $old_thumb;
        }
        Ads::where('id', $id)
            ->update([
                'name' => $request->input('name'),
                'link' => $request->input('link'),
                'thumb' => $thumbnail,
                'user_id' => Auth::id(),
                'status' => $request->input('status')
            ]);
        Toastr::success('Thông báo', 'Cập nhật ads thành công');
        return redirect('admin/ads/list');
    }
    public function delete($id)
    {
        Ads::destroy($id);
        Toastr::success('Thông báo', 'Xoá ads thành công');
        return redirect('admin/ads/list');
    }
}
