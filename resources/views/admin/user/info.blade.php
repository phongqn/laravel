@extends('layouts.admin')
@section('title', 'Thông tin đăng nhập')
@section('content')
<div id="wp-content">
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title mt-2">Thông tin</h3>
            </div>
            <div class="panel-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Tên</td>
                            <td><strong>{{ Auth::user()->name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Chức vụ</td>
                            <td><strong>Quản trị viên</strong></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td> {{ Auth::user()->email }}</td>
                        </tr>
                        <tr>
                            <td>Ngày tham gia</td>
                            <td> {{ Auth::user()->created_at }}</td>
                        </tr>
                        <tr>
                            <td><a href="#">Đổi mật khẩu</a></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection