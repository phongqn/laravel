@extends('layouts.admin')
@section('title', 'Danh sách trang')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách trang</h5>
                <div class="form-search form-inline">
                    <form action="#">
                        <input type="text" class="form-control form-search" name="q"
                            value="{{ request()->input('q') }}" placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Hoạt động<span
                            class="text-muted">({{ $count[0] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}" class="text-primary">Thùng rác
                        <span class="text-muted">({{ $count[1] }})</span></a>
                </div>
                <form action="{{ url('admin/page/action') }}">
                    @csrf
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name="action">
                            <option>Chọn</option>
                            @foreach ($list_action as $key => $action)
                                <option value="{{ $key }}">{{ $action }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col">STT</th>
                                <th scope="col">Tiêu đề</th>
                                <th scope="col">Người tạo</th>
                                <th scope="col">Ngày tạo</th>
                                @if (request()->input('status') == 'trash')
                                    <th scope="col">Ngày xóa</th>
                                @endif
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $t = 0;
                            @endphp
                            @if ($pages->total() > 0)
                                @foreach ($pages as $page)
                                    @php     $t++; @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $page->id }}">
                                        </td>
                                        <td> {{ $t }}</td>
                                        <td scope="row">{{ $page->title }}</td>
                                        <td>{{ $page->user->name }}</td>
                                        <td>{{ $page->created_at->format('H:i:s d-m-Y') }}</td>
                                        @if (request()->input('status') == 'trash')
                                            <td>{{ $page->deleted_at->format('H:i:s d-m-Y') }}</td>
                                        @endif
                                        <td>
                                            @if (request()->input('status') == 'trash')
                                            @else
                                                <a href="{{ route('edit.page', $page->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                            @endif
                                            <a href="{{ route('delete.page', $page->id) }}"
                                                onclick="return confirm('Bạn chắc chắn xóa bản ghi này')"
                                                class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="bg-white">Không có dữ liệu bản ghi</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{ $pages->links() }}
            </div>
        </div>
    </div>
@endsection
