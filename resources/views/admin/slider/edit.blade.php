@extends('layouts.admin')
@section('title', 'Cập nhật slider')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm slider
            </div>
            <div class="card-body">
                <form action="{{ url('admin/slider/update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Tên slider</label>
                        <input class="form-control" type="text" name="name" id="name"
                            value="{{ $slider->name }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>
                            <h6> Ảnh đại diện</h6>
                        </label>
                        <br>
                        <input type="file" name="slider_link" class=""> <br>
                        <img id="avatar" class="d-block w-25" src="{{ url($slider->slider_link) }}">
                        @error('slider_link')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="number_order">Số thứ tự</label>
                        <select class="form-control" id="number_order" name="number_order">
                            <option>Số thứ tự</option>
                            <option @if ($slider->number_order == 1) selected @endif  value="1">1</option>
                            <option @if ($slider->number_order == 2) selected @endif value="2">2</option>
                            <option @if ($slider->number_order == 3) selected @endif value="2">3</option>
                        </select>
                        @error('number_order')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Trạng Thái</label>
                        <select class="form-control" id="status" name="status">
                            <option>Chọn trạng thái</option>
                            <option @if ($slider->status == 0) selected @endif value="0">Công khai</option>
                            <option  @if ($slider->status == 1) selected @endif value="1">Chờ duyệt</option>
                        </select>
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
