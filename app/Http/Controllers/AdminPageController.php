<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class AdminPageController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'page']);
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

            $pages = Page::onlyTrashed()->latest()->paginate(5);
        } else {
            $list_action = [
                'delete' => 'Xóa tạm thời',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
            $keyword = "";
            if ($request->input('q')) {
                $keyword = $request->input('q');
            }
            $pages = Page::where('title', 'LIKE', "%{$keyword}%")->paginate(5);
        }
        $count_active = Page::count();
        $count_trash = Page::onlyTrashed()->count();
        $count = [$count_active, $count_trash];

        return view('admin/page/list', compact('pages', 'count', 'list_action'));
    }

    public function action(Request $request)
    {
        //lấy danh sách id phần tử đã check
        $list_check = $request->input('list_check');
        if ($list_check) {
            if (!empty($list_check)) {
                $action = $request->input('action');
                if ($action == 'restore') {
                    Page::withTrashed()
                        ->whereIn('id', $list_check)
                        ->restore();
                    Toastr::success('Thông báo', 'Khôi phục thành công');
                    return redirect('admin/page/list');
                }
                //xoá vĩnh viễn
                if ($action == 'forceDelete') {
                    Page::withTrashed()->whereIn('id', $list_check)->forceDelete();
                    Toastr::success('Thông báo', 'Xoá vĩnh viễn thành công');
                    return redirect('admin/page/list');
                }
                if ($action == 'delete') {
                    Page::destroy($list_check);
                    Toastr::success('Thông báo', 'Xoá tạm thành công');
                    return redirect('admin/page/list');
                }
            }
        } else {
            Toastr::error('Thông báo', 'Bạn chưa chọn phần tử cần thao tác');
            return redirect('admin/page/list');
        }
    }
    public function add()
    {
        return view('admin/page/add');
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|string|min:6|unique:pages',
                'content' => 'required|string'
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có ít nhất :min ký tự'
            ],
            [
                'title' => 'Tiêu đề',
                'content' => 'Nội dung trang',
            ]
        );
        if (empty($slug)) {
            $slug = Str::slug($request->input('title'));
        }
        Page::create([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'content' => $request->input('content'),
            'user_id' => Auth::id(),
        ]);
        Toastr::success('Thông báo', 'Thêm thành công');
        return redirect('admin/page/list');
    }
    public function delete($id)
    {
        Page::destroy($id);
        Toastr::success('Thông báo', 'Xoá thành công');
        return redirect('admin/page/list');
    }
    public function edit($id)
    {
        $page = Page::find($id);
        return view('admin.page.edit', compact('page'));
    }
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'title' => 'required|string|min:6',
                'content' => 'required|string'
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có ít nhất :min ký tự'
            ],
            [
                'title' => 'Tiêu đề',
                'content' => 'Nội dung trang',
            ]
        );
        if (empty($slug)) {
            $slug = Str::slug($request->input('title'));
        }
        Page::where('id', $id)->update([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'content' => $request->input('content'),
            'user_id' => Auth::id(),
        ]);
        Toastr::success('Thông báo', 'Cập nhật thành công');
        return redirect('admin/page/list');
    }
}
