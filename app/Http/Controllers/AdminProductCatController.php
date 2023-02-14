<?php

namespace App\Http\Controllers;

use App\Models\ProductCat;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class AdminProductCatController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);
            return $next($request);
        });
    }
    public function list()
    {
        $cat_proudcts = ProductCat::latest()->paginate(10);
        $data_select = dataSelect(new ProductCat);
        return view('admin/ProductCat/list', compact('cat_proudcts', 'data_select'));
    }
    public function add()
    {
        $data_select = dataSelect(new ProductCat);
        return view('admin/ProductCat/add', compact('data_select'));
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|min:4|unique:product_cats',
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có ít nhất :min ký tự',
                'unique' => ':attribute đã tồn tại trong hệ thống'
            ],
            [
                'name' => 'Danh mục',
            ]
        );
        ProductCat::create([
            'name' =>  $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'parent_id' => $request->input('parent')
        ]);
        Toastr::success('Thông báo', 'Thêm danh mục thành công');
        return redirect('admin/product/cat/list');
    }
    public function edit($id)
    {
        $product_cats = ProductCat::find($id);
        $data_select = dataSelect(new ProductCat);
        return view('admin/ProductCat/edit', compact('data_select', 'product_cats'));
    }
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|string|min:4',
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có ít nhất :min ký tự'
            ],
            [
                'name' => 'Danh mục',
            ]
        );
        ProductCat::where('id', $id)
            ->update([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name')),
                'parent_id' => $request->input('parent')
            ]);
        Toastr::success('Thông báo', 'Cập nhật danh mục thành công');
        return redirect('admin/product/cat/list');
    }
    public function delete($id)
    {
        ProductCat::destroy($id);
        Toastr::success('Thông báo', 'Xoá thành công');
        return redirect('admin/product/cat/list');
    }
}
