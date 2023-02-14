<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCat;
use App\Models\Slider;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    //
    public function index()
    {
        //lấy slider
        $list_sliders = Slider::where('status', '0')->get();
        //lấy sản phẩm nổi bật
        $outstanding_product = Product::where('outstanding_product', '0 ')->where('status', '0')->get();
        $product_selling = Product::where('product_selling', '0')->where('status', '0')->get();
        $catChildPhone = ProductCat::where('parent_id', function ($query) {
            $query->select('id')->from('product_cats')->where('slug', '=', 'dien-thoai');
        })->get();
        foreach ($catChildPhone as $item) {
            $catPhoneIds[] = $item->id;
        }
        $cat_phone_list = ProductCat::whereIn('parent_id', $catPhoneIds)->get();
        foreach ($cat_phone_list as $item) {
            $list_phone_cat_id[] = $item->id;
        }
        $cat_phone_list_2 = ProductCat::whereIn('parent_id', $list_phone_cat_id)->get();
            $product_phone = Product::whereIn('cat_id', $list_phone_cat_id)->where('status', '0')->get();
        return $product_phone;
        // $catChildLap = ProductCat::where('parent_id', function ($query) {
        //     $query->select('id')->from('product_cats')->where('slug', '=', 'may-tinh');
        // })->get();
        // foreach ($catChildLap as $item) {
        //     $catChildLap[] = $item->id;
        // }
        //return $catChildLap;



        return view('guest.home', compact('list_sliders', 'product_selling', 'outstanding_product'));
    }
}
