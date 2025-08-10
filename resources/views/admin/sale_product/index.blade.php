@extends('layouts.admin')

@section('title', '⚡ Chi tiết Sale sản phẩm')

@section('content')
<style>
    .sale-detail-card {
        max-width: 800px;
        margin: 30px auto;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 30px 40px;
        animation: fadeIn 0.4s ease-in-out;
    }

    .sale-header {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
    }

    .sale-header h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #4e73df;
        margin: 0 auto;
    }

    .btn-toggle {
        border: none;
        border-radius: 8px;
        padding: 8px 20px;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-toggle-on {
        background: #1cc88a;
    }
    .btn-toggle-off {
        background: #e74a3b;
    }
    .btn-toggle:hover {
        opacity: 0.9;
        transform: scale(1.05);
    }

    .sale-detail-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        align-items: center;
    }
    .sale-detail-label {
        font-weight: 600;
        color: #6b7280;
    }
    .sale-detail-value input {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 0.95rem;
        width: 100%;
    }

    .btn-save {
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        border: none;
        border-radius: 8px;
        padding: 8px 20px;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.95rem;
        transition: background 0.3s ease, transform 0.2s ease;
    }
    .btn-save:hover {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        transform: translateY(-1px);
    }

    .btn-back {
        background: #e5e7eb;
        border-radius: 8px;
        padding: 8px 20px;
        color: #374151;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.3s ease;
    }
    .btn-back:hover {
        background: #d1d5db;
    }
    .switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 28px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #e74a3b; /* Màu đỏ khi tắt */
    transition: 0.4s;
    border-radius: 34px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #1cc88a; /* Màu xanh khi bật */
}

input:checked + .slider:before {
    transform: translateX(22px);
}

</style>

<div class="sale-detail-card">
    <div class="sale-header">
        <h3><i class="bi bi-tag-fill"></i> Chi tiết Sale</h3>
    </div>

    <form id="updateSaleForm">
        @csrf
        <div class="sale-detail-row">
            <div class="sale-detail-label">ID:</div>
            <div class="sale-detail-value">{{ $sale->id_advice }}</div>
        </div>
        <div class="sale-detail-row">
            <div class="sale-detail-label">Sản phẩm:</div>
            <div class="sale-detail-value">{{ $sale->product->name_product }}</div>
        </div>
        <div class="sale-detail-row">
            <div class="sale-detail-label">Phần trăm giảm:</div>
            <div class="sale-detail-value" style="width: 60px">
                <input type="number" id="value" value="{{ $sale->value }}" >
            </div>
        </div>
        <div class="sale-detail-row">
            <div class="sale-detail-label">Ngày bắt đầu:</div>
            <div class="sale-detail-value">
                <input type="date" id="start_date" value="{{ \Carbon\Carbon::parse($sale->start_date)->format('Y-m-d') }}">
            </div>
        </div>
        <div class="sale-detail-row">
            <div class="sale-detail-label">Ngày kết thúc:</div>
            <div class="sale-detail-value">
                <input type="date" id="end_date" value="{{ \Carbon\Carbon::parse($sale->end_date)->format('Y-m-d') }}">
            </div>
        </div>
        <div class="sale-detail-row">
            <div class="sale-detail-label">Trạng thái:</div>
            <div class="sale-detail-value">
                <label class="switch">
                    <input type="checkbox" id="toggleSwitch" {{ $sale->status == 'on' ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-between">
            <a href="{{ route('admin.products.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <button type="button" id="saveBtn" class="btn-save">
                <i class="bi bi-save"></i> Lưu thay đổi
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('saveBtn').addEventListener('click', function () {
    let formData = {
        value: document.getElementById('value').value,
        start_date: document.getElementById('start_date').value,
        end_date: document.getElementById('end_date').value,
        _token: '{{ csrf_token() }}'
    };

    fetch("{{ route('admin.sale.update', $sale->id_advice) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || "Cập nhật thành công!");
    })
    .catch(error => {
        console.error(error);
        alert('Có lỗi xảy ra!');
    });
});

document.getElementById('toggleSwitch').addEventListener('change', function () {
    const status = this.checked ? 'on' : 'off';

    fetch("{{ route('admin.sale.toggleStatus', $sale->id_advice) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message || 'Trạng thái đã được cập nhật!');
    })
    .catch(err => {
        console.error(err);
        alert('Có lỗi xảy ra khi cập nhật trạng thái!');
    });
});

</script>
@endsection
