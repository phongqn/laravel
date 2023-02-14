@extends('layouts.admin')
@section('title', 'Cập nhật sản phẩm')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Cập nhật sản phẩm
            </div>
            <div class="card-body">
                <form action="{{ url('admin/product/update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Tên sản phẩm</label>
                        <input class="form-control" type="text" name="name" id="name"
                            value="{{ $product->name }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="product_code">Mã sản phẩm</label>
                        <input class="form-control" type="text" name="product_code" id="product_code"
                            value="{{ $product->product_code }}">
                        @error('product_code')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="qty">Số lượng</label>
                        <input class="form-control" type="number" min="1" name="qty" id="qty"
                            value="{{ $product->qty }}">
                        @error('qty')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="original_price">Giá</label>
                        <input class="form-control" type="number" name="original_price" id="original_price"
                            value="{{ $product->original_price }}">
                        @error('original_price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="price_sale">Giá sale</label>
                        <input class="form-control" type="number" name="price_sale" id="price_sale"
                            value="{{ $product->price_sale }}">
                        @error('price_sale')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="desc">Mô tả ngắn</label>
                        <textarea name="desc" class="form-control" cols="30" rows="5">{{ $product->desc }}</textarea>
                        @error('desc')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="content">Nội sản phẩm</label>
                        <textarea name="content" class="form-control" id="content" cols="30" rows="5">{{ $product->detail }}</textarea>
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
                        <img id="avatar" class="d-block" width="150px" src="{{ url($product->product_thumb) }}">
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
                        @foreach ($product_thumbs as $thumb)
                            <img id="avatar" class="w-25 " src="{{ url($thumb->thumb_link) }}">
                        @endforeach
                        @error('product_thumbs')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input class="" type="checkbox" name="outstanding_product"
                            @if ($product->outstanding_product == 0) checked='checked' @endif id="outstanding_product"
                            value="0">
                        <label for="outstanding_product">Sản phẩm nổi bật</label>
                    </div>
                    <div class="form-group">
                        <input class="" type="checkbox" name="product_selling"
                            @if ($product->product_selling == 1) checked='checked' @endif id="product_selling" value="1">
                        <label for="product_selling">Sản phẩm bán chạy</label>
                    </div>
                    <div class="form-group">
                        <label for="product_cats">Danh mục</label>
                        <select class="form-control" id="products" name="product_cats">
                            <option value="0">Chọn danh mục</option>
                            @foreach ($product_cats as $key => $value)
                                @if ($product->cat_id == $key)
                                    <option selected value="{{ $key }}">{{ $value }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
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
                            <option @if ($product->status == 0) selected @endif value="0">Công khai</option>
                            <option @if ($product->status == 1) selected @endif value="1">Chờ duyệt</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
