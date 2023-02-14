@extends('layouts.admin')
@section('title', 'Danh sách thành viên')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách thành viên</h5>
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
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}" class="text-primary">Thùng rác<span
                            class="text-muted">({{ $count[1] }})</span></a>
                </div>
                <form action="{{ url('admin/user/action') }}" method="">
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
                                <th>
                                    <input type="checkbox" name="checkall">
                                </th>
                                <th scope="col">Stt</th>
                                <th scope="col">Họ tên</th>
                                <th scope="col">Email</th>
                                <th scope="col">Quyền</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($users->total() > 0)
                                @php
                                    $t = 0;
                                @endphp
                                @foreach ($users as $user)
                                    @php     $t++; @endphp
                                    <tr>
                                        <td>
                                            @if (Auth::id() != $user->id)
                                                <input type="checkbox" name="list_check[]" value="{{ $user->id }}">
                                            @endif
                                        </td>
                                        <th scope="row">{{ $t }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>Admintrator</td>
                                        <td>{{ $user->created_at->format('H:i:s d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('edit.user', $user->id) }}"
                                                class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                            @if (Auth::id() != $user->id)
                                                <a href="{{ route('delete_user', $user->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn xoá vĩnh viễn !')"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"></i></a>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="bg-white">Không tìm thấy bản ghi !</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
