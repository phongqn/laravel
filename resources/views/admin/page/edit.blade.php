@extends('layouts.admin')
@section('title', 'Sửa trang')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Sửa Trang
            </div>
            <div class="card-body">
                <form method="POST" action="{{ url('admin/page/update', $page->id) }}">
                    @csrf
                    <div class="form-group">
                        <label for="title">Tiêu đề trang</label>
                        <input class="form-control" type="text" name="title" id="title"
                            value=" {{ $page->title }}">
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="content">Nội dung trang</label>
                        <textarea name="content" class="form-control content" id="content" cols="30" rows="8">{{ $page->content }}</textarea>
                        @error('content')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" value="Cập nhật" class="btn btn-primary" name="btn-update">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
