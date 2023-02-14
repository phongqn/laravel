@extends('layouts.admin')
@section('title', 'Cập nhật bài viết')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm bài viết
            </div>
            <div class="card-body">
                <form action="{{ url('admin/post/update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Tiêu đề bài viết</label>
                        <input class="form-control" type="text" name="title" id="title"
                            value="{{ $post->title }}">
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content">Nội dung bài viết</label>
                        <textarea name="content" value="" class="form-control" id="content" cols="30" rows="5">{{ $post->content }}</textarea>
                        @error('content')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form">
                        <label>
                            <h6> Ảnh đại diện</h6>
                        </label>
                        <br>

                        <input type="file" name="post_thumb" class="" value="{{ old('post_thumb') }}"> <br> <br>
                        <img id="avatar" class="d-block" width="150px" src="{{ url($post->post_thumb) }}">
                        @error('post_thumb')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="post_cats">Danh mục</label>
                        <select class="form-control" id="" name="post_cats">
                            <option value="0">Chọn danh mục</option>
                            @foreach ($post_cats as $key => $value)
                                @if ($post->cat_id == $key)
                                    <option selected value="{{ $key }}">{{ $value }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('post_cats')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Trạng Thái</label>
                        <select class="form-control" id="status" name="status">
                            <option>Chọn trạng thái</option>
                            <option @if ($post->status == 0) selected @endif value="0">Công khai</option>
                            <option @if ($post->status == 1) selected @endif value="1">Chờ duyệt</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
