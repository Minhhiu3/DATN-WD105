@extends('layouts.admin')

@section('title', '➕ Thêm size sản phẩm')

@section('content')
<style>
    body {
        background: #f0f2f5;
    }

    .card-clean {
        max-width: 600px;
        margin: 50px auto;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 40px;
        animation: fadeIn 0.4s ease-in-out;
    }

    .card-clean h3 {
        font-size: 1.6rem;
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

    .form-control {
        border-radius: 12px;
        border: 1px solid #d1d5db;
        background-color: #f9fafb;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
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
    .is-invalid {
    border-color: #dc2626; /* đỏ */
    background-color: #fef2f2;
}


    body { background: #f0f2f5; }
    .card-clean { max-width: 600px; margin: 50px auto; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 40px; animation: fadeIn 0.4s ease-in-out; }
    .card-clean h3 { font-size: 1.6rem; font-weight: 700; color: #38bdf8; text-align: center; margin-bottom: 25px; }
    .form-label { font-weight: 600; color: #374151; margin-bottom: 8px; }
    .form-control { border-radius: 12px; border: 1px solid #d1d5db; background-color: #f9fafb; padding: 12px 15px; font-size: 1rem; transition: all 0.3s ease; }
    .form-control:focus { border-color: #38bdf8; box-shadow: 0 0 0 0.15rem rgba(56,189,248,0.3); background: #fff; }
    .is-invalid { border-color: #dc2626; background-color: #fef2f2; }
    .text-danger { font-size: 0.9rem; margin-top: 4px; }
    .btn-primary-custom { background: linear-gradient(135deg,#38bdf8,#0ea5e9); border: none; border-radius: 12px; padding: 12px 30px; color: #fff; font-weight: 600; font-size: 1rem; transition: background 0.3s ease, transform 0.2s ease; }
    .btn-primary-custom:hover { background: linear-gradient(135deg,#0ea5e9,#0284c7); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(56,189,248,0.4); }
    .btn-secondary-custom { background: #e5e7eb; border: none; border-radius: 12px; padding: 12px 30px; color: #374151; font-weight: 600; font-size: 1rem; transition: background-color 0.3s ease; }
    .btn-secondary-custom:hover { background: #d1d5db; }
    @keyframes shake {
    0% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    50% { transform: translateX(5px); }
    75% { transform: translateX(-5px); }
    100% { transform: translateX(0); }
    }
    .shake {
    animation: shake 0.3s;
    }

</style>

<div class="card-clean">
    <h3><i class="bi bi-plus-circle"></i> Thêm Size Sản phẩm</h3>

    <!-- Flash messages -->
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.sizes.store') }}" method="POST" id="size-form">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Tên size</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" placeholder="Nhập tên size mới" >
                       <!-- Hiển thị lỗi từng trường -->

                <div class="error-message text-danger">
                    @error('name')
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    @enderror
                </div>           
        </div>
        <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn-primary-custom">
                <i class="bi bi-check-circle"></i> Thêm mới
            </button>
            <a href="{{ route('admin.sizes.index') }}" class="btn-secondary-custom">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>
<script>

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('size-form');

    const rules = {
        name: { required: true, integer: true, max: 255,min:1 },
    };

    const messages = {
        name: {
            required: 'Vui lòng nhập size sản phẩm.',
            integer: 'Tên size không hợp lệ.',
            max: 'Tên size không được vượt quá 255 ký tự.',
            min: 'Tên size không được nhập giá trị âm.'
        },
    };

    function getGroup(input){
        return input.closest('.mb-3, .mb-4') || input.parentNode;
    }

    function showError(input, message) {
        const group = getGroup(input);
        const errorDiv = group.querySelector('.error-message');
        if (errorDiv) errorDiv.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
        input.classList.add('is-invalid');
        const fileBox = group.querySelector('.file-upload');
        if (fileBox) fileBox.classList.add('border-danger');
    }

    function clearError(input) {
        const group = getGroup(input);
        const errorDiv = group.querySelector('.error-message');
        if (errorDiv) errorDiv.textContent = '';
        input.classList.remove('is-invalid');
        const fileBox = group.querySelector('.file-upload');
        if (fileBox) fileBox.classList.remove('border-danger');
    }

    function validateField(input) {
        const name = input.name.replace('[]', ''); // album[]
        const rule = rules[name];
        if (!rule) return true;

        const isFile = input.type === 'file';
        const value = isFile ? '' : (input.value || '').trim();

        // required
        if (rule.required) {
            if (isFile) {
                if (input.files.length === 0) {
                    showError(input, messages[name].required);
                    return false;
                }
            } else if (!value) {
                showError(input, messages[name].required);
                return false;
            }
        }
        //integerss
    // integer
    if (rule.integer) {
        let num = Number(value);
        if (!Number.isInteger(num)) {
            showError(input, messages[name].integer);
            return false;
        }
        // min
        if (rule.min !== undefined && num < rule.min) {
            showError(input, messages[name].min);
            return false;
        }
        // max
        if (rule.max !== undefined && num > rule.max) {
            showError(input, messages[name].max);
            return false;
        }
    }

        clearError(input);
        return true;
    }

    // Bắt sự kiện
    const inputs = form.querySelectorAll('input');

    inputs.forEach(input => {
        const h = () => validateField(input);
        input.addEventListener('input', h);
        input.addEventListener('blur', h);
        if (input.type === 'file') input.addEventListener('change', h);
    });

    form.addEventListener('submit', function(e) {
        let isValid = true;
        inputs.forEach(input => { if (!validateField(input)) isValid = false; });
        if (!isValid) e.preventDefault();
    });
});
</script>

@endsection
