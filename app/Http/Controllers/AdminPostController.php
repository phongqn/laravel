<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCat;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminPostController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'post']);
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
            $posts = Post::onlyTrashed()->latest()->paginate(5);
        } else if ($status == 'pending') {
            $list_action = [
                'delete' => 'Xóa tạm thời',
                'forceDelete' => 'Xóa vĩnh viễn',
                'public' => 'Công khai'
            ];
            $posts = Post::where('status', '1')->latest()->paginate(5);
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
            $posts = Post::where('title', 'LIKE', "%{$keyword}%")->where('status', '0')->paginate(5);
        }
        $count['all'] = Post::withTrashed()->count();
        $count['public'] = Post::where('status', '0')->count();
        $count['trash'] = Post::onlyTrashed()->count();
        $count['pending'] = Post::where('status', '1')->count();
        return view('admin/post/list', compact('posts', 'list_action', 'count'));
    }
    public function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if ($list_check) {
            if (!empty($list_check)) {
                $action = $request->input('action');
                if ($action == 'restore') {
                    Post::withTrashed()
                        ->whereIn('id', $list_check)
                        ->restore();
                    Toastr::success('Thông báo', 'Khôi phục thành công');
                    return redirect('admin/post/list');
                }
                if ($action == 'forceDelete') {
                    Post::withTrashed()->whereIn('id', $list_check)->forceDelete();
                    Toastr::success('Thông báo', 'Xoá vĩnh viễn thành công');
                    return redirect('admin/post/list');
                }
                if ($action == 'delete') {
                    Post::destroy($list_check);
                    Toastr::success('Thông báo', 'Xoá tạm thành công');
                    return redirect('admin/post/list');
                }
                if ($action == 'public') {
                    Post::whereIn('id', $list_check)->update(['status' => '0']);
                    Toastr::success('Thông báo', 'Chuyển bài viết công khai thành công');
                    return redirect('admin/post/list');
                }
                if ($action == 'pending') {
                    Post::whereIn('id', $list_check)->update(['status' => '1']);
                    Toastr::success('Thông báo', 'Chuyển bài viết thành tạm thời thành công');
                    return redirect('admin/post/list');
                }
            }
        } else {
            Toastr::error('Thông báo', 'Bạn chưa chọn phần tử cần thao tác');
            return back();
        }
    }
    public function add()
    {
        $post_cats = dataSelect(new PostCat);
        return view('admin/post/add', compact('post_cats'));
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|min:8|unique:posts',
                'post_thumb' => 'required',
                'content' => 'required|min:10',
                'post_cats' => 'required'
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có ít nhất :min ký tự',
                'unique' => ':attribute đã tồn tại trong hệ thống'
            ],
            [
                'title' => 'Tiêu đề',
                'post_thumb' =>  'Ảnh bài viết',
                'content' => 'Nội dung bài viết',
                'post_cats' => 'Danh mục bài viết'
            ]
        );
        if ($request->hasFile('post_thumb')) {
            $file = $request->file('post_thumb');
            // $fileName = $file->getClientOriginalName();
            $fileName = Str::slug($request->input('title'));
            $file_ex = $file->getClientOriginalExtension();
            $file->move('public/admin/uploads/post', $fileName . '.' . $file_ex);
            $thumbnail = 'public/admin/uploads/post/' . $fileName . '.' . $file_ex;
        }


        Post::create([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'content' => $request->input('content'),
            'post_thumb' => $thumbnail,
            'cat_id' => $request->input('post_cats'),
            'user_id' => Auth::id(),
            'status' => $request->input('status')
        ]);
        Toastr::success('Thông báo', 'Thêm bài viết thành công');
        return redirect('admin/post/list');
    }
    // public function checkStatus(Request $request)
    // {
    //     $check_box = $request->input('checkbox_name');
    //     return $check_box;
    //     // Toastr::success('Thông báo', 'Cập nhật trạng thái thành công');
    //     // return redirect('admin/post/list');
    // }

    public function edit($id)
    {
        $post_cats = dataSelect(new PostCat);
        $post = Post::find($id);
        return view('admin.post.edit', compact('post', 'post_cats'));
    }
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        $old_thumb = $post->post_thumb;
        $request->validate(
            [
                'title' => 'required|min:8|',
                'post_thumb' => 'img',
                'content' => 'required|min:10',
                'post_cats' => 'required'
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có ít nhất :min ký tự',
                'unique' => ':attribute đã tồn tại trong hệ thống'
            ],
            [
                'title' => 'Tiêu đề',
                'post_thumb' => 'Ảnh bài viết',
                'content' => 'Nội dung bài viết',
                'post_cats' => 'Danh mục bài viết'
            ]
        );

        if ($request->hasFile('post_thumb')) {
            Storage::delete(url($old_thumb));
            $file = $request->file('post_thumb');
            // $fileName = $file->getClientOriginalName();
            $fileName = Str::slug($request->input('title'));
            $file_ex = $file->getClientOriginalExtension();
            $file->move('public/admin/uploads/post', $fileName . '.' . $file_ex);
            $thumbnail = 'public/admin/uploads/post/' . $fileName . '.' . $file_ex;;
        } else {
            $thumbnail =  $old_thumb;
        }
        Post::where('id', $id)
            ->update([
                'title' => $request->input('title'),
                'slug' => Str::slug($request->input('title')),
                'content' => $request->input('content'),
                'post_thumb' => $thumbnail,
                'cat_id' => $request->input('post_cats'),
                'user_id' => Auth::id(),
                'status' => $request->input('status')
            ]);
        Toastr::success('Thông báo', 'Cập nhật bài viết thành công');
        return redirect('admin/post/list');
    }
    public function delete($id)
    {
        Post::destroy($id);
        Toastr::success('Thông báo', 'Bạn đã xoá thành công');
        return redirect('admin/post/list');
    }
}
