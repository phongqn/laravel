<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class AdminSliderController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'slider']);
            return $next($request);
        });
    }
    public function list(Request $request)
    {
        $status = $request->input('status');
        if ($status == 'trash') {
            $list_action = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
            $sliders = Slider::onlyTrashed()->latest()->paginate(5);
        } else if ($status == 'pending') {
            $list_action = [
                'delete' => 'Xóa tạm thời',
                'forceDelete' => 'Xóa vĩnh viễn',
                'public' => 'Công khai'
            ];
            $sliders = Slider::where('status', '1')->latest()->paginate(5);
        } else {
            $list_action = [
                'delete' => 'Xóa tạm thời',
                'forceDelete' => 'Xóa vĩnh viễn',
                'pending' => 'Tạm ẩn'
            ];
            $keyword = "";
            if ($request->input('q')) {
                $keyword = $request->input('q');
            }
            $sliders = Slider::where('name', 'LIKE', "%{$keyword}%")->where('status', '0')->paginate(5);
        }
        $count['all'] = Slider::withTrashed()->count();
        $count['public'] = Slider::where('status', '0')->count();
        $count['trash'] = Slider::onlyTrashed()->count();
        $count['pending'] = Slider::where('status', '1')->count();
        return view('admin/slider/list', compact('sliders', 'list_action', 'count'));
    }
    public function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if ($list_check) {
            if (!empty($list_check)) {
                $action = $request->input('action');
                if ($action == 'restore') {
                    Slider::withTrashed()
                        ->whereIn('id', $list_check)
                        ->restore();
                        Toastr::success('Thông báo', 'Khôi phục thành công');
                        return redirect('admin/slider/list');
                }
            }
            if ($action == 'forceDelete') {
                Slider::withTrashed()->whereIn('id', $list_check)->forceDelete();
                Toastr::success('Thông báo', 'Xoá vĩnh viễn thành công');
                return redirect('admin/slider/list');
            }
            if ($action == 'delete') {
                Slider::destroy($list_check);
                Toastr::success('Thông báo', 'Xoá tạm thành công');
                return redirect('admin/slider/list');
            }
            if ($action == 'public') {
                Slider::whereIn('id', $list_check)->update(['status' => '0']);
                Toastr::success('Thông báo', 'Chuyển slider công khai thành công');
                return redirect('admin/slider/list');
            }
            if ($action == 'pending') {
                Slider::whereIn('id', $list_check)->update(['status' => '1']);
                Toastr::success('Thông báo', 'Chuyển slider thành tạm thời thành công');
                return redirect('admin/slider/list');
            }
        } else {
            Toastr::error('Thông báo', 'Bạn chưa chọn phần tử cần thao tác');
            return back();
        }
    }
    public function add()
    {
        return view('admin/slider/add');
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|min:8|unique:sliders',
                'slider_link' => 'required',
                'status' => 'required',
                'number_order' => 'required'
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có ít nhất :min ký tự',
                'unique' => ':attribute đã tồn tại trong hệ thống'
            ],
            [
                'name' => 'Tên slider',
                'slider_link' =>  'Ảnh slider',
                'status' => 'Trạng thái',
                'number_order' => 'Số thứ tự',
            ]
        );
        if ($request->hasFile('slider_link')) {
            $file = $request->file('slider_link');
            // $fileName = $file->getClientOriginalName();
            $fileName = Str::slug($request->input('name'));
            $file_ex = $file->getClientOriginalExtension();
            $file->move('public/admin/uploads/slider', $fileName . '.' . $file_ex);
            $thumbnail = 'public/admin/uploads/slider/' . $fileName . '.' . $file_ex;
        }
        Slider::create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'slider_link' => $thumbnail,
            'user_id' => Auth::id(),
            'number_order' => $request->input('number_order'),
            'status' => $request->input('status')
        ]);
        Toastr::success('Thông báo', 'Thêm slider thành công');
        return redirect('admin/slider/list');
    }
    public function edit($id)
    {
        
        $slider = Slider::find($id);
        return view('admin/slider/edit', compact('slider'));
    }
    public function update(Request $request, $id)
    {  $slider = Slider::find($id);
        $old_thumb = $slider->slider_link;
        $request->validate(
            [
                'name' => 'required|min:8|',
                'status' => 'required',
                'number_order' => 'required'
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có ít nhất :min ký tự',
                'unique' => ':attribute đã tồn tại trong hệ thống'
            ],
            [
                'name' => 'Tên slider',
                'slider_link' =>  'Ảnh slider',
                'status' => 'Trạng thái',
                'number_order' => 'Số thứ tự',
            ]
        );
        if ($request->hasFile('slider_link')) {
            $file = $request->file('slider_link');
            // $fileName = $file->getClientOriginalName();
            $fileName = Str::slug($request->input('name'));
            $file_ex = $file->getClientOriginalExtension();
            $file->move('public/admin/uploads/slider', $fileName . '.' . $file_ex);
            $thumbnail = 'public/admin/uploads/slider/' . $fileName . '.' . $file_ex;
        }else {
            $thumbnail =  $old_thumb;
        }
        Slider::where('id', $id)
        ->update([  
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'slider_link' => $thumbnail,
            'user_id' => Auth::id(),
            'number_order' => $request->input('number_order'),
            'status' => $request->input('status')
         ]);
         Toastr::success('Thông báo', 'Cập nhật slider thành công');
         return redirect('admin/slider/list');
    }
    public function delete($id)
    {
        Slider::destroy($id);
        Toastr::success('Thông báo', 'Bạn đã xoá slider thành công');
        return redirect('admin/slider/list');
    }
}
