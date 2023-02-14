@extends('layouts.admin')
@section('title', 'Danh sách danh mục bài viết')
@section('content')

    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header font-weight-bold ">
                        <h5>Danh mục</h5>
                        <a href="{{ url('admin/post/cat/add') }}" type="button" class=" float-right btn btn-primary">Thêm
                            danh mục</a>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Tên danh mục</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Danh mục cha</th>
                                    <th scope="col">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $t = 0;
                                @endphp
                                @foreach ($cat_posts as $item)
                                    @php $t++; @endphp
                                    <tr>
                                        <th scope="row">{{ $t }}</th>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->slug }}</td>
                                        @if ($item->parent_id == 0)
                                            <td>Null</td>
                                        @else
                                            <td>{{ $item->catPostParent->name }}</td>
                                           
                                        @endif  
                                        <td>
                                            <a href="{{ route('edit.post.cat', $item->id) }}"
                                                class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                            <a href="{{ route('delete.post.cat', $item->id) }}"
                                                onclick="return confirm('Bạn chắc chắn xóa bản ghi này')"
                                                class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                     {{-- @include('admin.postCat.sub', ['catPostChild' => $item->catPostChild]) --}}
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $cat_posts->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
