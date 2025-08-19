@extends('layouts.client_home')
@section('title', 'Mã Giảm Giá')
@section('content')

<style>
    .voucher-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        padding: 20px 0;
    }

    .voucher-card {
        background: #fff;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .voucher-card:hover {
        transform: translateY(-5px);
    }

    .voucher-header {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .voucher-icon {
        width: 50px;
        height: 50px;
        background: #f39c12;
        color: white;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .voucher-content {
        flex: 1;
    }

    .voucher-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 4px;
    }

    .voucher-details {
        font-size: 14px;
        color: #555;
    }

    .voucher-expire {
        font-size: 13px;
        color: #888;
        margin-top: 10px;
    }

    .voucher-footer {
        margin-top: 15px;
        text-align: right;
    }

    .btn-save {
        background: #f39c12;
        color: #fff;
        padding: 6px 16px;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        transition: background 0.3s ease;
    }
    .btn-use {
        background: #f5f3f0;
        color: #131111;
        padding: 6px 16px;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        transition: background 0.3s ease;
    }
    .btn-save:hover {
        background: #d35400;
    }
</style>

<section class="container my-5">
    <h2 class="text-center mb-4">🧧 Mã Giảm Giá Hôm Nay</h2>

    <div class="voucher-grid">
        @foreach($discountCodes as $voucher)
        <!-- Voucher 1 -->
        @if($voucher->type === '0')
            <div class="voucher-card">
                <div class="voucher-header">
                    <div class="voucher-icon">
                        
                            %

                    </div>
                    <div class="voucher-content">
                        <div class="voucher-title">Giảm {{ number_format(($voucher->value), 0, ',', '.') }}%</div>
                        <div class="voucher-details">Mã: {{$voucher->code}}</div>
                    </div>
                </div>
                <div class="voucher-details">Áp dụng cho đơn từ {{ number_format(($voucher->min_order_value), 0, ',', '.') }} vnd</div>
                <div class="voucher-expire">HSD: {{$voucher->end_date}}</div>
                <div class="voucher-footer">
                   @csrf
                                   @if (Auth::check() && $voucher->userVouchers->isNotEmpty())
                    
                    <button 
                        class="btn-use btn-use-voucher" 
                    >
                        Đã lưu mã
                    </button>
                @else
                    <button 
                    class="btn-save btn-save-voucher" 
                    data-discount-id="{{ $voucher->discount_id }}"
                    >
                    Lưu mã
                    </button>
                @endif
                </div>
            </div>
        @elseif($voucher->type === '1')
             <div class="voucher-card">
                <div class="voucher-header">
                    <div class="voucher-icon">
                        
                            đ

                    </div>
                    <div class="voucher-content">
                        <div class="voucher-title">Giảm {{ number_format(($voucher->value), 0, ',', '.') }}vnđ</div>
                        <div class="voucher-details">Mã: {{$voucher->code}}</div>
                    </div>
                </div>
                <div class="voucher-details">Áp dụng cho đơn từ {{ number_format(($voucher->min_order_value), 0, ',', '.') }} vnd</div>
                <div class="voucher-expire">HSD: {{$voucher->end_date}}</div>
                <div class="voucher-footer">
                    <form action="/save-voucher-user" method="POST">
                        @csrf
                                   @if (Auth::check() && $voucher->userVouchers->isNotEmpty())
                    
                    <button 
                        class="btn-use btn-use-voucher" 
                    >
                        Đã lưu mã
                    </button>
                @else
                    <button 
                    class="btn-save btn-save-voucher" 
                    data-discount-id="{{ $voucher->discount_id }}"
                    >
                    Lưu mã
                    </button>
                @endif
                    
       
                    </form>
                </div>
            </div>
        @else
            🔥
        @endif
         @endforeach
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Lấy tất cả button lưu mã
  const saveButtons = document.querySelectorAll('.btn-save-voucher');

  saveButtons.forEach(button => {
    button.addEventListener('click', async (event) => {
      event.preventDefault();  // Ngăn reload trang khi click nút

      const discountId = button.dataset.discountId;

      // Lấy token CSRF từ meta tag
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      try {
        const response = await fetch('/save-voucher-user', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
          },
          body: JSON.stringify({ discount_code_id: discountId }),
        });

        const data = await response.json();
   if (response.status === 401) {
        window.location.href = '{{ route('login') }}';
        alert('Bạn cần đăng nhập đẻ lưu mã !');

          return;
        }
        if (response.ok && data.success) {
          alert('Lưu mã thành công!');
          // Thay đổi giao diện nút sau khi lưu
          button.textContent = 'Đã lưu';
          button.disabled = true;
          location.reload();

        } else {
          alert(data.message || 'Lưu mã thất bại!');
        }
      } catch (error) {
        console.error('Lỗi khi lưu voucher:', error);
        alert('Có lỗi xảy ra, vui lòng thử lại!');
      }
    });
  });
});

</script>

@endsection
