@extends('layouts.admin')
@section('title', 'Thêm ads')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm ads
            </div>
            <div class="card-body">
                <form action="{{ url('admin/ads/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Tên ads</label>
                        <input class="form-control" type="text" name="name" id="name"
                            value="{{ old('name') }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Link ads</label>
                        <input class="form-control" type="text" name="link" id="link"
                            value="{{ old('link') }}">
                        @error('link')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>
                            <h6> Ảnh đại diện</h6>
                        </label>
                        <br>
                        <input type="file" name="thumb" class=""> <br>
                        @error('thumb')
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
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection
