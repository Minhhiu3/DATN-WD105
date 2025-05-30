@extends('layouts.admin')

@section('title', 'Quản lý Danh mục sản phẩm')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách danh mục sản phẩm</h3>
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

        </table>
    </div>
</div>
@endsection
