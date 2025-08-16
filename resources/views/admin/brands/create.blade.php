
@extends('layouts.admin')

@section('title', 'Thêm Mới Thương Hiệu')

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
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm thương hiệu mới</h3>
    </div>
    <div class="card-body">


        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên thương hiệu</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" >
                @error('name')
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            let input = document.getElementById("name");
                            input.classList.add("shake", "is-invalid");
                            setTimeout(() => input.classList.remove("shake"), 400);
                        });
                    </script>
                    <div class="text-danger mt-1" style="font-size:0.9rem;">
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="logo" class="form-label">Logo</label>
                <input type="file" name="logo" id="logo" class="form-control">
                
                @error('logo')
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            let input = document.getElementById("logo");
                            if (input) {
                                input.classList.add("shake", "is-invalid");
                                setTimeout(() => input.classList.remove("shake"), 400);
                            }
                        });
                    </script>
                    <div class="text-danger mt-1" style="font-size:0.9rem;">
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>


            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select name="status" id="status" class="form-select">
                    <option value="visible" {{ old('status') == 'visible' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="hidden" {{ old('status') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection
