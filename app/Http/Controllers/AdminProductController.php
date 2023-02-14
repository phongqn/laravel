<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCat;
use Illuminate\Support\Str;
use App\Models\ProductThumb;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);
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
            $products = Product::onlyTrashed()->latest()->paginate(10);
        } else if ($status == 'pending') {
            $list_action = [
                'delete' => 'Xóa tạm thời',
                'forceDelete' => 'Xóa vĩnh viễn',
                'public' => 'Công khai'
            ];
            $products = Product::where('status', '1')->latest()->paginate(10);
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
            $products = Product::where('name', 'LIKE', "%{$keyword}%")->where('status', '0')->paginate(10);
        }
        $count['all'] = Product::withTrashed()->count();
        $count['public'] = Product::where('status', '0')->count();
        $count['trash'] = Product::onlyTrashed()->count();
        $count['pending'] = Product::where('status', '1')->count();
        return view('admin/product/list', compact('products', 'list_action', 'count'));
    }
    public function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if ($list_check) {
            if (!empty($list_check)) {
                $action = $request->input('action');
                if ($action == 'restore') {
                    Product::withTrashed()
                        ->whereIn('id', $list_check)
                        ->restore();
                    Toastr::success('Thông báo', 'Khôi phục thành công');
                    return redirect('admin/product/list');
                }
            }
            if ($action == 'forceDelete') {
                Product::withTrashed()->whereIn('id', $list_check)->forceDelete();
                Toastr::success('Thông báo', 'Xoá vĩnh viễn thành công');
                return redirect('admin/product/list');
            }
            if ($action == 'delete') {
                Product::destroy($list_check);
                Toastr::success('Thông báo', 'Xoá tạm thành công');
                return redirect('admin/product/list');
            }
            if ($action == 'public') {
                Product::whereIn('id', $list_check)->update(['status' => '0']);
                Toastr::success('Thông báo', 'Chuyển sản phẩm công khai thành công');
                return redirect('admin/product/list');
            }
            if ($action == 'pending') {
                Product::whereIn('id', $list_check)->update(['status' => '1']);
                Toastr::success('Thông báo', 'Chuyển sản phẩm thành tạm thời thành công');
                return redirect('admin/product/list');
            }
        } else {
            Toastr::error('Thông báo', 'Bạn chưa chọn phần tử cần thao tác');
            return back();
        }
    }
    public function add()
    {
        $product_cats = dataSelect(new ProductCat);
        return view('admin/product/add', compact('product_cats'));
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|min:8|unique:products',
                'product_code' => 'required',
                'product_thumb' => 'required',
                'qty' => 'required',
                'product_thumbs' => 'required',
                'original_price' => 'required',
                'desc' => 'required|min:10',
                'content' => 'required|min:10',
                'product_cats' => 'required',
                'status' => 'required'
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có ít nhất :min ký tự',
                'unique' => ':attribute đã tồn tại trong hệ thống'
            ],
            [
                'name' => 'Tên sản phẩm',
                'product_code' => 'Mã sản phẩm',
                'product_thumb' => 'Ảnh đại diện',
                'product_thumbs' => 'Ảnh chi tiết',
                'original_price' => 'Giá gốc',
                'qty' => 'Số lượng',
                'desc' => 'Mô tả ngắn',
                'product_thumbs' => 'Ảnh sản phẩm',
                'content' => 'Nội dung sản phẩm',
                'product_cats' => 'Danh mục sản phẩm',
                'status' => 'Trạng thái'
            ]
        );
        $data_insert = ([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'desc' => $request->input('desc'),
            'detail' => $request->input('content'),
            'product_code' => $request->input('product_code'),
            'qty' => $request->input('qty'),
            'original_price' => $request->input('original_price'),
            'price_sale' => $request->input('price_sale'),
            'product_selling' => $request->input('product_selling'),
            'outstanding_product' => $request->input('outstanding_product'),
            'cat_id' => $request->input('product_cats'),
            'user_id' => Auth::id(),
            'status' => $request->input('status')
        ]);
        if ($request->hasFile('product_thumb')) {
            $file = $request->file('product_thumb');
            // $fileName = $file->getClientOriginalName();
            $fileName = Str::slug($request->input('name'));
            $file_ex = $file->getClientOriginalExtension();
            $file->move('public/admin/uploads/product', $fileName . '.' . $file_ex);
            $thumbnail = 'public/admin/uploads/product/' . $fileName . '.' . $file_ex;
        }
        $data_insert['product_thumb'] = $thumbnail;
        $products = Product::create($data_insert);
        if ($request->hasFile('product_thumbs')) {
            $files = $request->file('product_thumbs');
            foreach ($files as $file) {
                $fileName = $file->getClientOriginalName();
                $filePath =  $file->move('public/admin/uploads/product', $fileName);
                ProductThumb::create([
                    'product_id' => $products->id,
                    'thumb_link' => $filePath
                ]);
            }
        }
        Toastr::success('Thông báo', 'Thêm sản phẩm thành công');
        return redirect('admin/product/list');
    }
    public function edit($id)
    {
        $product = Product::find($id);
        $product_thumbs = $product->thumb;
        // return $product_thumbs;
        $product_cats = dataSelect(new ProductCat);
        return view('admin/product/edit', compact('product_cats', 'product', 'product_thumbs'));
    }
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|min:8|',
                'product_code' => 'required',
                'qty' => 'required',
                'original_price' => 'required',
                'desc' => 'required|min:10',
                'content' => 'required|min:10',
                'product_cats' => 'required',
                'status' => 'required'
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có ít nhất :min ký tự'
            ],
            [
                'name' => 'Tên sản phẩm',
                'product_code' => 'Mã sản phẩm',
                'product_thumb' => 'Ảnh đại diện',
                'product_thumbs' => 'Ảnh chi tiết',
                'original_price' => 'Giá gốc',
                'qty' => 'Số lượng',
                'desc' => 'Mô tả ngắn',
                'product_thumbs' => 'Ảnh sản phẩm',
                'content' => 'Nội dung sản phẩm',
                'product_cats' => 'Danh mục sản phẩm',
                'status' => 'Trạng thái'
            ]
        );
        $data_update = ([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'desc' => $request->input('desc'),
            'detail' => $request->input('content'),
            'product_code' => $request->input('product_code'),
            'qty' => $request->input('qty'),
            'original_price' => $request->input('original_price'),
            'price_sale' => $request->input('price_sale'),
            'product_selling' => $request->input('product_selling'),
            'outstanding_product' => $request->input('outstanding_product'),
            'cat_id' => $request->input('product_cats'),
            'user_id' => Auth::id(),
            'status' => $request->input('status')
        ]);
        $product = Product::find($id);
        $old_thumb = $product->product_thumb;
        if ($request->hasFile('product_thumb')) {
            Storage::delete(url($old_thumb));
            $file = $request->file('product_thumb');
            // $fileName = $file->getClientOriginalName();
            $fileName = Str::slug($request->input('name'));
            $file_ex = $file->getClientOriginalExtension();
            $file->move('public/admin/uploads/product', $fileName . '.' . $file_ex);
            $thumbnail = 'public/admin/uploads/product/' . $fileName . '.' . $file_ex;
        } else {
            $thumbnail =  $old_thumb;
        }
        $data_update['product_thumb'] = $thumbnail;
        Product::where('id', $id)->update($data_update);

        //chi tiết ảnh
        if ($request->hasFile('product_thumbs')) {
            $files = $request->file('product_thumbs');
            ProductThumb::where('product_id', $id)->delete();
            foreach ($files as $file) {
                $fileName = $file->getClientOriginalName();
                $filePath =  $file->move('public/admin/uploads/product', $fileName);
                ProductThumb::create([
                    'product_id' =>   $id,
                    'thumb_link' => $filePath
                ]);
            }
        }
        Toastr::success('Thông báo', 'Cập nhật sản phẩm thành công');
        return redirect('admin/product/list');
    }
    public function delete($id)
    {
        Product::destroy($id);
        Toastr::success('Thông báo', 'Xoá sản phẩm thành công');
        return redirect('admin/product/list');
    }
}
