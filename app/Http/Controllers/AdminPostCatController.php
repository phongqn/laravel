<?php

namespace App\Http\Controllers;

use App\Models\PostCat;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class AdminPostCatController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'post']);
            return $next($request);
        });
    }
    public function list()
    {
        $cat_posts = PostCat::latest()->paginate(10);
        $data_select = dataSelect(new PostCat);
        return view('admin/postCat/list', compact('cat_posts', 'data_select'));
    }
    public function add()
    {
        $data_select = dataSelect(new PostCat);
        return view('admin/postCat/add', compact('data_select'));
    }
    public function store(Request $request)
    {
        $name_exist = PostCat::where('name', $request->input('cat_name'))->exists();
        if ($name_exist) {
            return redirect('admin/post/cat/add')->with('error', 'Tên danh mục đã tồn tại !');
        } else {
            $name_cat = $request->input('cat_name');
        }
        $request->validate(
            [
                'cat_name' => 'required|string|min:4',
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có ít nhất :min ký tự'
            ],
            [
                'cat_name' => 'Danh mục',
            ]
        );
        PostCat::create([
            'name' =>  $name_cat,
            'slug' => Str::slug($request->input('cat_name')),
            'parent_id' => $request->parent
        ]);
        Toastr::success('Thông báo', 'Thêm danh mục thành công');
        return redirect('admin/post/cat/list');
    }
    public function edit($id)
    {
        $post_cats = PostCat::find($id);
        $data_select = dataSelect(new PostCat);
        return view('admin/postCat/edit', compact('data_select', 'post_cats'));
    }
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'cat_name' => 'required|string|min:4',
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có ít nhất :min ký tự'
            ],
            [
                'cat_name' => 'Danh mục',
            ]
        );
        PostCat::where('id', $id)
            ->update([
                'name' => $request->input('cat_name'),
                'slug' => Str::slug($request->input('cat_name')),
                'parent_id' => $request->parent
            ]);
        Toastr::success('Thông báo', 'Cập nhật danh mục thành công');
        return redirect('admin/post/cat/list');
    }
    public function delete($id)
    {
        PostCat::destroy($id);
        Toastr::success('Thông báo', 'Xoá thành công');
        return redirect('admin/post/cat/list');
    }
}
