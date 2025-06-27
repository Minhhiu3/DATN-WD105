@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Chi tiết đơn hàng</h3>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

            <div class="border p-3 mb-4 rounded shadow-sm">
                {{-- Thông tin người dùng --}}
                <h5>🧍 Thông tin khách hàng</h5>
                <ul>
                    <li><strong>Tên:</strong> {{ $user->name ?? 'N/A' }}</li>
                    <li><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</li>
                    <li><strong>Số điện thoại:</strong> {{ $user->phone_number }}</li>
                    <li><strong>Địa chỉ:</strong> {{ $user->phone_number }}</li>
                </ul>
                
            </div>

            <div class="border p-3 mb-4 rounded shadow-sm">
                {{-- Danh sách sản phẩm --}}
                <h5 class="mt-3">📦 Sản phẩm đã đặt</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-secondary">
                            <tr>
                                <th></th>
                                <th>Sản phẩm</th>
                                <th>Size</th>
                                <th>Số Lượng</th>
                                <th>Giá</th>
                                <th>Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                            $tongTien = 0; 
                            @endphp
                            @foreach ($order_items as $item)
                                @php
                                    $price = optional($item->variant)->price ?? 0;
                                    $quantity = $item->quantity ?? 0;
                                    $thanhTien = $price * $quantity;
                                    $tongTien += $thanhTien;
                                @endphp
                                <tr>
                                    <td>{{ $item->variant->id_variant ?? 'Không rõ' }}</td>
                                    <td>{{ $item->variant->product->name_product ?? 'Không rõ' }}</td>
                                    <td>{{ $item->variant->size->name ?? 'Không rõ' }}</td>
                                    <td>{{ $item->quantity ?? 'Không rõ' }}</td>
                                    <td>{{ number_format($item->variant->price ?? 0, 0, ',', '.') }} VND</td>
                                    <td>{{ number_format($thanhTien, 0, ',', '.') }} VND</td>

                                </tr>
                            @endforeach
                                {{-- Dòng tổng tiền --}}
                                <tr class="table-success">
                                    <td colspan="5" class="text-end"><strong>Tổng tiền:</strong></td>
                                    <td><strong>{{ number_format($tongTien, 0, ',', '.') }} VND</strong></td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>

    </div>
</div>
@endsection
