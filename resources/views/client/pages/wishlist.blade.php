@extends('layouts.client_home')

@section('title', 'Danh sách yêu thích')

@push('styles')
<style>
    .wishlist-container {
        padding: 40px 0;
        background-color: #f8f9fa;
        min-height: 70vh;
    }

    .wishlist-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .wishlist-header h1 {
        color: #333;
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .wishlist-header p {
        color: #666;
        font-size: 1.1rem;
    }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .wishlist-item {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }

    .wishlist-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .product-image {
        position: relative;
        height: 250px;
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .wishlist-item:hover .product-image img {
        transform: scale(1.05);
    }

    .wishlist-remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 2;
    }

    .wishlist-remove-btn:hover {
        background: #ff4757;
        color: white;
    }

    .product-info {
        padding: 20px;
    }

    .product-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-brand {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 10px;
    }

    .product-price {
        font-size: 1.3rem;
        font-weight: 700;
        color: #e74c3c;
        margin-bottom: 15px;
    }

    .product-actions {
        display: flex;
        gap: 10px;
    }

    .btn-add-cart {
        flex: 1;
        background: #3498db;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
    }

    .btn-add-cart:hover {
        background: #2980b9;
        color: white;
        text-decoration: none;
    }

    .btn-view-detail {
        background: #2ecc71;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
    }

    .btn-view-detail:hover {
        background: #27ae60;
        color: white;
        text-decoration: none;
    }

    .empty-wishlist {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .empty-wishlist i {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 20px;
    }

    .empty-wishlist h3 {
        color: #666;
        margin-bottom: 15px;
    }

    .empty-wishlist p {
        color: #999;
        margin-bottom: 30px;
    }

    .btn-shop-now {
        background: #3498db;
        color: white;
        padding: 12px 30px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-shop-now:hover {
        background: #2980b9;
        color: white;
        text-decoration: none;
    }

    .wishlist-actions {
        text-align: center;
        margin-bottom: 30px;
    }

    .btn-clear-wishlist {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-clear-wishlist:hover {
        background: #c0392b;
    }

    .loading {
        text-align: center;
        padding: 40px;
    }

    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
        .wishlist-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .wishlist-header h1 {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="wishlist-container">
    <div class="container">
        <div class="wishlist-header">
            <h1><i class="fas fa-heart"></i> Danh sách yêu thích</h1>
            <p>Những sản phẩm bạn đã thêm vào danh sách yêu thích</p>
        </div>

        @if($wishlistItems->count() > 0)
            <div class="wishlist-actions">
                <button type="button" class="btn-clear-wishlist" onclick="clearWishlist()">
                    <i class="fas fa-trash"></i> Xóa tất cả
                </button>
            </div>

            <div class="wishlist-grid" id="wishlist-grid">
                @foreach($wishlistItems as $item)
                    <div class="wishlist-item" data-product-id="{{ $item->id_product }}">
                        <div class="product-image">
                            @if($item->albumProducts->count() > 0)
                                <img src="{{ asset('storage/' . $item->albumProducts->first()->image) }}" 
                                     alt="{{ $item->name_product }}" 
                                     onerror="this.src='{{ asset('assets/img/no-image.jpg') }}'">
                            @else
                                <img src="{{ asset('assets/img/no-image.jpg') }}" alt="{{ $item->name_product }}">
                            @endif
                            
                            <button type="button" class="wishlist-remove-btn" 
                                    onclick="removeFromWishlist({{ $item->id_product }})"
                                    title="Xóa khỏi danh sách yêu thích">
                                <i class="fas fa-heart-broken"></i>
                            </button>
                        </div>
                        
                        <div class="product-info">
                            <h3 class="product-name">{{ $item->name_product }}</h3>
                            @if($item->brand)
                                <p class="product-brand">{{ $item->brand->name_brand }}</p>
                            @endif
                            <div class="product-price">
                                {{ number_format($item->price, 0, ',', '.') }} ₫
                            </div>
                            
                            <div class="product-actions">
                                <a href="{{ route('client.product.show', $item->id_product) }}" 
                                   class="btn-view-detail">
                                    <i class="fas fa-eye"></i> Xem chi tiết
                                </a>
                                <button type="button" class="btn-add-cart" 
                                        onclick="addToCart({{ $item->id_product }})">
                                    <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $wishlistItems->links() }}
            </div>
        @else
            <div class="empty-wishlist">
                <i class="fas fa-heart-broken"></i>
                <h3>Danh sách yêu thích trống</h3>
                <p>Bạn chưa có sản phẩm nào trong danh sách yêu thích</p>
                <a href="{{ route('products') }}" class="btn-shop-now">
                    <i class="fas fa-shopping-bag"></i> Mua sắm ngay
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// CSRF token setup
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Remove from wishlist
function removeFromWishlist(productId) {
    Swal.fire({
        title: 'Xác nhận',
        text: 'Bạn có chắc chắn muốn xóa sản phẩm này khỏi danh sách yêu thích?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("wishlist.remove") }}',
                method: 'POST',
                data: {
                    product_id: productId
                },
                success: function(response) {
                    if (response.success) {
                        // Remove item from DOM
                        $(`[data-product-id="${productId}"]`).fadeOut(300, function() {
                            $(this).remove();
                            
                            // Check if wishlist is empty
                            if ($('#wishlist-grid .wishlist-item').length === 0) {
                                location.reload();
                            }
                        });
                        
                        // Update wishlist count in header if exists
                        updateWishlistCount();
                        
                        Swal.fire({
                            title: 'Thành công!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra khi xóa sản phẩm',
                        icon: 'error'
                    });
                }
            });
        }
    });
}

// Clear all wishlist
function clearWishlist() {
    Swal.fire({
        title: 'Xác nhận',
        text: 'Bạn có chắc chắn muốn xóa tất cả sản phẩm khỏi danh sách yêu thích?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Xóa tất cả',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("wishlist.clear") }}',
                method: 'POST',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Thành công!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra khi xóa danh sách yêu thích',
                        icon: 'error'
                    });
                }
            });
        }
    });
}

// Add to cart
function addToCart(productId) {
    $.ajax({
        url: '{{ route("cart.add") }}',
        method: 'POST',
        data: {
            product_id: productId,
            quantity: 1
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Thành công!',
                    text: 'Đã thêm sản phẩm vào giỏ hàng',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Update cart count in header if exists
                updateCartCount();
            }
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Lỗi!',
                text: 'Có lỗi xảy ra khi thêm vào giỏ hàng',
                icon: 'error'
            });
        }
    });
}

// Update wishlist count in header
function updateWishlistCount() {
    $.ajax({
        url: '{{ route("wishlist.count") }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                // Update wishlist count in header if element exists
                const wishlistCountElement = document.querySelector('.wishlist-count');
                if (wishlistCountElement) {
                    wishlistCountElement.textContent = response.count;
                }
            }
        }
    });
}

// Update cart count in header
function updateCartCount() {
    $.ajax({
        url: '{{ route("cart.count") }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                // Update cart count in header if element exists
                const cartCountElement = document.querySelector('.cart-count');
                if (cartCountElement) {
                    cartCountElement.textContent = response.count;
                }
            }
        }
    });
}
</script>
@endpush
