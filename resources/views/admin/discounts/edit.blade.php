@extends('layouts.admin')

@section('title', 'Quản lý Mã Giảm Giá')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('discounts.update',$discount->discount_id) }}" method="POST">
            @csrf
              @method('PUT')
            <div class="mb-3">
                <label for="code" class="form-label">Mã giảm giá</label>
                <input type="text" name="code" id="code" class="form-control" value="{{ old('code',$discount->code) }}"  placeholder="Nhập mã giảm giá" required >
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Loại</label>
                <input type="text" name="type" id="type" class="form-control" value="{{ old('type',$discount->type) }}"  placeholder="Nhập loại (ví dụ:0:percentage, 1:fixed)" required >
            </div>
            <div class="mb-3">
                <label for="value" class="form-label">Giá trị</label>
                <input type="number" step="0.01" name="value" id="value" class="form-control" value="{{ old('value',$discount->value) }}" placeholder="Nhập giá trị" required >
            </div>
            <div class="mb-3">
                <label for="max_discount" class="form-label">Giảm tối đa</label>
                <input type="number" step="0.01" name="max_discount" id="max_discount" class="form-control" value="{{ old('max_discount',$discount->max_discount) }}" placeholder="Nhập giảm tối đa" required >
            </div>
            <div class="mb-3">
                <label for="min_order_value" class="form-label">Giá trị đơn tối thiểu</label>
                <input type="number" step="0.01" name="min_order_value" id="min_order_value" class="form-control" value="{{ old('min_order_value',$discount->min_order_value) }}" placeholder="Nhập giá trị đơn tối thiểu"  required >
            </div>
            {{-- <div class="mb-3">
                <label for="user_specific" class="form-label">Dành riêng người dùng</label>
                <input type="checkbox" name="user_specific" id="user_specific" value="1" {{ old('user_specific') ? 'checked' : '' }}>
            </div> --}}
            <div class="mb-3">
                <label for="start_date" class="form-label">Ngày bắt đầu</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date',$discount->start_date) }}" required>
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">Ngày kết thúc</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date',$discount->end_date) }}" required>
            </div>
            <div class="mb-3">
                <label for="is_active" class="form-label">Hoạt động</label>
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $discount->is_active) ? 'checked' : '0' }}>
            </div>
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('discounts.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection
