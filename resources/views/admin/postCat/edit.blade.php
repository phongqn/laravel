@extends('layouts.admin')
@section('title', 'Sửa danh mục bài viết')
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Cập nhật danh mục
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/post/cat/update', $post_cats->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên danh mục</label>
                                <input class="form-control" type="text" name="cat_name" id="name"
                                    value="{{ $post_cats->name }}">
                                @error('cat_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Danh mục cha</label>
                                <select class="form-control" id="" name="parent">
                                    <option value="0">Chọn danh mục cha</option>
                                    @foreach ($data_select as $key => $value)
                                        @if ($post_cats->parent_id == $key)
                                            <option selected value="{{ $key }}">{{ $value }}</option>
                                        @else
                                            @if ($post_cats->id == $key)
                                                <option hidden value="{{ $key }}">{{ $value }}</option>
                                            @else
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
