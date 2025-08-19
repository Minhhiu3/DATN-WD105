@extends('layouts.admin')

@section('title', 'Sửa Mã Giảm Giá')

@section('content')
<style>
    .card-clean {
        max-width: 700px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 35px;
        animation: fadeIn 0.4s ease-in-out;
    }

    .card-clean h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #38bdf8;
        text-align: center;
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select {
        border-radius: 12px;
        border: 1px solid #d1d5db;
        background-color: #f9fafb;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 0 0.15rem rgba(56, 189, 248, 0.3);
        background: #fff;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        color: #ffffff;
        font-weight: 600;
        font-size: 1rem;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(56, 189, 248, 0.4);
    }

    .btn-secondary-custom {
        background: #e5e7eb;
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        color: #374151;
        font-weight: 600;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .btn-secondary-custom:hover {
        background: #d1d5db;
    }
</style>

<div class="card-clean">
    <h2><i class="bi bi-pencil-square"></i> ✏️ Sửa Mã Giảm Giá</h2>

    <form action="{{ route('admin.discounts.update', $discount->discount_id) }}" method="POST" id="discount-form">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="code" class="form-label">Mã giảm giá</label>
            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror"
                value="{{ old('code', $discount->code) }}" placeholder="Nhập mã giảm giá" >
            <div class="error-message text-danger">
                @error('code')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Loại</label>
            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" >
                <option value="">-- Chọn loại giảm giá --</option>
                <option value="0" {{ old('type', $discount->type) == 0 ? 'selected' : '' }}>Phần trăm (%)</option>
                <option value="1" {{ old('type', $discount->type) == 1 ? 'selected' : '' }}>Giảm cố định (VND)</option>
            </select>
            <div class="error-message text-danger">
                @error('type')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="value" class="form-label">Giá trị</label>
            <input type="number"  name="value" id="value" class="form-control @error('value') is-invalid @enderror"
                value="{{ old('value', (int) $discount->value) }}" placeholder="Nhập giá trị" >
            <div class="error-message text-danger">
                @error('value')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="min_order_value" class="form-label">Giá trị đơn tối thiểu</label>
            <input type="number"  name="min_order_value" id="min_order_value" class="form-control @error('min_order_value') is-invalid @enderror"
                value="{{ old('min_order_value', (int) $discount->min_order_value) }}" placeholder="Nhập giá trị đơn tối thiểu" min="0" step="1000">
            <div class="error-message text-danger">
                @error('min_order_value')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="start_date" class="form-label">Ngày bắt đầu</label>
                <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror"
                    value="{{ old('start_date', $discount->start_date) }}" >
                <div class="error-message text-danger">
                    @error('start_date')
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    @enderror
                </div>     
            </div>
            <div class="col-md-6 mb-3">
                <label for="end_date" class="form-label">Ngày kết thúc</label>
                <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror"
                    value="{{ old('end_date', $discount->end_date) }}" >
                <div class="error-message text-danger">
                    @error('end_date')
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input "
                value="1" {{ old('is_active', $discount->is_active) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Hoạt động</label>
            <div class="error-message text-danger">
                @error('is_active')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn-primary-custom">
                <i class="bi bi-check-circle"></i> Lưu
            </button>
            <a href="{{ route('admin.discounts.index') }}" class="btn-secondary-custom">
                <i class="bi bi-arrow-left-circle"></i> Quay lại
            </a>
        </div>
    </form>
</div>
<script>


document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('discount-form');

    // Rules và messages từ Controller
    const rules = {
        code: { required: true, max: 50 , unique: true},
        type: { required: true, in: ['0', '1'] },
        value: { required: true, numeric: true, min: 1 },
        min_order_value: { required: true, numeric: true, min: 1000 },
        start_date: { required: true, date: true },
        end_date: { required: true, date: true, afterOrEqual: 'start_date' },
        is_active: { boolean: true }
    };
    const typeInput = form.querySelector('[name="type"]');
    const valueInput = form.querySelector('[name="value"]');

    // Khi type thay đổi
    
    const messages = {
        code: {
            required: 'Vui lòng nhập mã giảm giá.',
            max: 'Mã giảm giá không được vượt quá 50 ký tự.',
            
        },
        type: {
            required: 'Vui lòng chọn loại giảm giá.',
            in: 'Loại giảm giá không hợp lệ.'
        },

        value: {
            required: 'Vui lòng nhập giá trị giảm.',
            numeric: 'Giá trị giảm phải là số.',
            min: 'Giá trị giảm không được nhỏ hơn 0.',
            max: 'Khi chọn loại phần trăm, giá trị không được vượt quá 99%.'
        },
        min_order_value: {
            required: 'Vui lòng nhập giá trị đơn tối thiểu.',
            numeric: 'Giá trị đơn tối thiểu phải là số.',
            min: 'Giá trị đơn tối thiểu không được nhỏ hơn 1000.'
        },
        start_date: {
            required: 'Vui lòng chọn ngày bắt đầu.',
            date: 'Ngày bắt đầu không hợp lệ.'
        },
        end_date: {
            required: 'Vui lòng chọn ngày kết thúc.',
            date: 'Ngày kết thúc không hợp lệ.',
            afterOrEqual: 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.'
        },
        is_active: {
            boolean: 'Trạng thái hoạt động không hợp lệ.'
        }
    };
typeInput.addEventListener('change', function () {
         // Luôn giữ min = 0

        if (typeInput.value === '0') {
            // Giảm % → max = 100
            rules.value.min = 1;
            rules.value.max = 99;
            messages.value.min = 'Giá trị giảm không được nhỏ hơn 1.';
            messages.value.max = 'Khi chọn loại phần trăm, giá trị không được vượt quá 99%.';
            
        } else if (typeInput.value === '1') {
            // Giảm cố định → max không giới hạn
            rules.value.min = 1000;
            rules.value.max = undefined;
            // Cập nhật thông báo theo type
            messages.value.min = 'Giá trị giảm không được nhỏ hơn 1000.';
            messages.value.max = ''; // không cần thông báo max
        }
    })
    // Hàm hiển thị lỗi
    function showError(input, message) {
        const errorDiv = input.parentNode.querySelector('.error-message');
        errorDiv.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;

    }

    // Hàm xóa lỗi
    function clearError(input) {
        const errorDiv = input.parentNode.querySelector('.error-message');
        errorDiv.textContent = '';
    }

        async function checkCodeUnique(code) {
        try {
            const fieldName = input.name;
            const value = input.value.trim();
            const response = await fetch(`/check-code?code=${encodeURIComponent(code)}`);
            const result = await response.json();
            return result.isUnique;
        } catch (error) {
            console.error('Error checking code uniqueness:', error);
            return false;
        }
    }

    // Hàm kiểm tra validation
    function validateField(input) {
        const fieldName = input.name;
        const value = input.value.trim();

        if (rules[fieldName].required && !value) {
            input.classList.add('is-invalid');
            showError(input, messages[fieldName].required);
            return false;
        } else if (rules[fieldName].numeric && isNaN(value)) {
            input.classList.add('is-invalid');
            showError(input, messages[fieldName].numeric);
            return false;
        } else if (rules[fieldName].min && Number(value) < rules[fieldName].min) {
            input.classList.add('is-invalid');
            showError(input, messages[fieldName].min);
            return false;
        } else if (rules[fieldName].max && Number(value) > rules[fieldName].max) {
            input.classList.add('is-invalid');
            showError(input, messages[fieldName].max);
            return false;
        } else if (rules[fieldName].date && !isValidDate(value)) {
            input.classList.add('is-invalid');
            showError(input, messages[fieldName].date);
            return false;
        } else if (rules[fieldName].afterOrEqual && fieldName === 'end_date') {
            const startDate = document.querySelector('[name="start_date"]').value;
            if (new Date(value) < new Date(startDate)) {
                input.classList.add('is-invalid');
                showError(input, messages[fieldName].afterOrEqual);
                return false;
            }        else {
            input.classList.remove('is-invalid');
            clearError(input);
            return true;
        }
        } else if (rules[fieldName].boolean && !(value === 'true' || value === 'false')) {
            input.classList.add('is-invalid');
            showError(input, messages[fieldName].boolean);
            return false;
        } else if (rules[fieldName].in && !rules[fieldName].in.includes(value)) {
            input.classList.add('is-invalid');
            showError(input, messages[fieldName].in);
            return false;
        } 
        else {
            input.classList.remove('is-invalid');
            clearError(input);
            return true;
        }
    }

    // Hàm kiểm tra ngày hợp lệ
    function isValidDate(dateString) {
        return !isNaN(Date.parse(dateString));
    }
    

    // Bắt sự kiện input và blur
 const inputs = form.querySelectorAll('input, select'); // Đây mới là NodeList thực sự

inputs.forEach(input => {
    input.addEventListener('input', () => validateField(input));
    input.addEventListener('blur', () => validateField(input));
});

//  = form.querySelectorAll('input, select');

form.addEventListener('submit', function(e) {
    let isValid = true;

    inputs.forEach(input => {
        const errorMessage = validateField(input); // validateField trả về message nếu lỗi, null nếu đúng

        if (errorMessage) {
            isValid = false;
            showError(input, errorMessage); // ✅ hiện lỗi khi submit
        } else {
            clearError(input); // Nếu hợp lệ thì xóa lỗi cũ
        }
    });

    if (!isValid) {
        e.preventDefault(); // Ngăn submit nếu có lỗi
    }
});


});





</script>
@endsection
