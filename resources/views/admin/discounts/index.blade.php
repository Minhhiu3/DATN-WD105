@extends('layouts.admin')

@section('title', 'Quản lý Mã Giảm Giá')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách mã giảm giá</h3>
    </div>

    <div class="card-header d-flex justify-content-between align-items-center">
        <a href="{{ route('discounts.create') }}" class="btn btn-primary">Thêm mới</a>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mã giảm giá</th>
                    <th>Loại</th>
                    <th>Giá trị</th>
                     <th>Giảm tối đa</th>
                      <th>Đơn tối thiểu</th>
                       <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                    <th>Trạng thái</th>

                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($discounts as $discount)
                    <tr>
                        <td>{{ $discount->discount_id }}</td>
                        <td>{{ $discount->code }}</td>
                        <td>@if ($discount->type == 0)
                              %
                            @elseif ($discount->type == 1)
                                Giá trị
                            @else
                                Không xác định
                            @endif</td>
                       <td>@if ($discount->type == 0)
                              {{$discount->value }}( % )
                            @elseif ($discount->type == 1)
                                {{$discount->value }}
                            @else
                                Không xác định
                            @endif</td>
                          <td>{{ $discount->max_discount }}</td>
                            <td>{{ $discount->min_order_value }}</td>
                              <td>{{ $discount->start_date }}</td>
                                <td>{{ $discount->end_date }}</td>
                                    <td>@if ($discount->is_active == 0)
                               Không hoạt động
                            @elseif ($discount->is_active == 1)
                                 Hoạt động
                            @else
                                Không xác định
                            @endif</td>

                        <td>
                            <a href="{{ route('discounts.edit', $discount->discount_id) }}"
                                class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('discounts.destroy', $discount->discount_id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Bạn có chắc muốn xóa?')"
                                    class="btn btn-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
