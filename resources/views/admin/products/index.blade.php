@extends('admin.index')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách người dùng</h3>
    </div>
    <div class="card-body">
        <!-- Nội dung user -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
@endsection