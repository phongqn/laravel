@extends('layouts.admin')
@section('title', 'Trang danh sách Ads')
@section('content')

    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách bài viết</h5>
                <div class="form-search form-inline">
                    <form action="#">
                        <input type="" class="form-control form-search" placeholder="Tìm kiếm" name="q"
                            value="{{ request()->input('q') }}">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <small class="font-italic"> Có tất cả {{ $count['all'] }} ads trong hệ thống </small>
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'public']) }}" class="text-primary">Công khai<span
                            class="text-muted">({{ $count['public'] }})</span></a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="text-primary">Tạm ẩn<span
                            class="text-muted">({{ $count['pending'] }})</span></a>
                </div>
                <form action="{{ url('admin/ads/action') }}">
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
                                <th scope="col">Ảnh</th>
                                <th scope="col">Tên ads</th>
                                <th scope="col">Link ads</th>
                                <th scope="col">Người tạo</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $t = 0;
                            @endphp
                            @if ($ads->total() > 0)
                                @foreach ($ads as $item)
                                    @php     $t++; @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $item->id }}">
                                        </td>
                                        <td scope="row">{{ $t }}</td>
                                        <td><img class="img-fluid" src="{{ url($item->thumb) }}" width="80"
                                                height="80" alt=""></td>
                                        <td><a href="">{{ $item->name }}</a>
                                        <td><a href="">{{ $item->link }}</a>
                                        <td>{{ $item->user->name }}</td>

                                        <td>
                                            <form action="" id="form_id">
                                                @csrf
                                                <label class="switch">

                                                    @if (request()->input('status') == 'pending')
                                                        <input type="checkbox" value="{{ $item->id }}"
                                                            name="checkbox_name" id="checkbox_id">
                                                        <span class="sliderr round"></span>
                                                    @elseif(request()->input('status') == 'trash')
                                                        <input type="" value="{{ $item->id }}"
                                                            name="checkbox_name" id="checkbox_id">
                                                        <span class="sliderr round"></span>
                                                    @else
                                                        <input type="checkbox" checked value="{{ $item->id }}"
                                                            name="checkbox_name" id="checkbox_id">
                                                        <span class="sliderr round"></span>
                                                    @endif

                                                </label>
                                            </form>
                                        </td>

                                        <td>{{ $item->created_at->format('H:i:s d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('edit.ads', $item->id) }}"
                                                class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                            <a href="{{ route('delete.ads', $item->id) }}"
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
                {{ $ads->links() }}
            </div>
        </div>
    </div>
@endsection
