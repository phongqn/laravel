@extends('layouts.admin')
@section('title', 'Thêm sản phẩm')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm sản phẩm
            </div>
            <div class="card-body">
                <form action="{{ url('admin/product/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Tên sản phẩm</label>
                        <input class="form-control" type="text" name="name" id="name"
                            value="{{ old('name') }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="product_code">Mã sản phẩm</label>
                        <input class="form-control" type="text" name="product_code" id="product_code"
                            value="{{ old('product_code') }}">
                        @error('product_code')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="qty">Số lượng</label>
                        <input class="form-control" type="number" min="1" name="qty" id="qty"
                            value="{{ old('qty') }}">
                        @error('qty')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="original_price">Giá</label>
                        <input class="form-control" type="number"name="original_price" id="original_price"
                            value="{{ old('original_price') }}">
                        @error('original_price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="price_sale">Giá sale</label>
                        <input class="form-control" type="number" name="price_sale" id="price_sale"
                            value="{{ old('price_sale') }}">
                        @error('price_sale')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="desc">Mô tả ngắn</label>
                        <textarea name="desc" value="{{ old('desc') }}" class="form-control" cols="30" rows="5"></textarea>
                        @error('desc')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="content">Nội sản phẩm</label>
                        <textarea name="content" value="{{ old('content') }}" class="form-control" id="content" cols="30"
                            rows="5"></textarea>
                        @error('content')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>
                            <h6> Ảnh đại diện</h6>
                        </label>
                        <br>

                        <input type="file" name="product_thumb" class=""> <br>
                        @error('product_thumb')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>
                            <h6> Ảnh chi tiết</h6>
                        </label>
                        <br>

                        <input type="file" name="product_thumbs[]" class="" multiple> <br>
                        @error('product_thumbs')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input class="" type="checkbox" name="outstanding_product" id="outstanding_product"
                            value="0">
                        <label for="outstanding_product">Sản phẩm nổi bật</label>
                    </div>
                    <div class="form-group">
                        <input class="" type="checkbox" name="product_selling" id="product_selling" value="0">
                        <label for="product_selling">Sản phẩm bán chạy</label>
                    </div>
                    <div class="form-group">
                        <label for="product_cats">Danh mục</label>
                        <select class="form-control" id="products" name="product_cats">
                            <option value="0">Chọn danh mục</option>
                            @foreach ($product_cats as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('product_cats')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Trạng Thái</label>
                        <select class="form-control" id="status" name="status">
                            <option>Chọn trạng thái</option>
                            <option value="0">Công khai</option>
                            <option value="1">Chờ duyệt</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection
