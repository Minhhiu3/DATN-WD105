# Chức năng Sản phẩm Yêu thích (Wishlist)

## Tổng quan
Chức năng wishlist cho phép người dùng đã đăng nhập lưu trữ các sản phẩm yêu thích để xem lại sau này.

## Các tính năng đã implement

### 1. Database
- **Bảng `wishlists`**: Lưu trữ mối quan hệ giữa user và product
- **Migration**: `2025_08_21_000000_create_wishlists_table.php`
- **Foreign keys**: 
  - `user_id` → `users.id_user`
  - `product_id` → `products.id_product`
- **Unique constraint**: Ngăn chặn duplicate entries

### 2. Models
- **Wishlist Model**: Quản lý dữ liệu wishlist
- **User Model**: Thêm relationships `wishlists()` và `wishlistProducts()`
- **Product Model**: Thêm relationships `wishlists()` và `wishlistUsers()`

### 3. Controller
- **WishlistController**: Xử lý tất cả logic wishlist
  - `index()`: Hiển thị danh sách wishlist
  - `add()`: Thêm sản phẩm vào wishlist
  - `remove()`: Xóa sản phẩm khỏi wishlist
  - `toggle()`: Bật/tắt wishlist cho sản phẩm
  - `check()`: Kiểm tra trạng thái wishlist
  - `count()`: Đếm số lượng sản phẩm trong wishlist
  - `clear()`: Xóa tất cả sản phẩm khỏi wishlist

### 4. Routes
Tất cả routes được bảo vệ bởi middleware `auth`:
- `GET /wishlist` - Trang danh sách wishlist
- `POST /wishlist/add` - Thêm vào wishlist
- `POST /wishlist/remove` - Xóa khỏi wishlist
- `POST /wishlist/toggle` - Bật/tắt wishlist
- `POST /wishlist/check` - Kiểm tra trạng thái
- `GET /wishlist/count` - Lấy số lượng
- `POST /wishlist/clear` - Xóa tất cả

### 5. Views
- **Trang wishlist**: `resources/views/client/pages/wishlist.blade.php`
  - Hiển thị danh sách sản phẩm yêu thích
  - Pagination
  - Nút xóa từng sản phẩm
  - Nút xóa tất cả
  - Nút thêm vào giỏ hàng
  - Responsive design

### 6. UI Components
- **Nút wishlist trong trang chi tiết sản phẩm**:
  - Hiển thị trạng thái wishlist
  - AJAX toggle functionality
  - Loading states
  - Success/error notifications

- **Nút wishlist trong trang danh sách sản phẩm**:
  - Icon heart với hover effects
  - AJAX functionality
  - Real-time status updates

- **Header wishlist icon**:
  - Hiển thị số lượng sản phẩm trong wishlist
  - Link đến trang wishlist
  - Chỉ hiển thị cho user đã đăng nhập

### 7. JavaScript Features
- **AJAX calls**: Tất cả operations đều sử dụng AJAX
- **Real-time updates**: Cập nhật UI ngay lập tức
- **Loading states**: Hiển thị trạng thái loading
- **Error handling**: Xử lý lỗi và hiển thị thông báo
- **SweetAlert2**: Thông báo đẹp mắt

## Cách sử dụng

### 1. Thêm sản phẩm vào wishlist
- Trong trang chi tiết sản phẩm: Click nút "Thêm vào yêu thích"
- Trong trang danh sách sản phẩm: Click icon heart

### 2. Xem danh sách wishlist
- Click vào icon heart trong header
- Hoặc truy cập trực tiếp `/wishlist`

### 3. Xóa sản phẩm khỏi wishlist
- Trong trang wishlist: Click nút "Xóa" trên từng sản phẩm
- Hoặc click lại nút wishlist trong trang sản phẩm

### 4. Xóa tất cả wishlist
- Trong trang wishlist: Click nút "Xóa tất cả"

## Security
- Tất cả routes được bảo vệ bởi middleware `auth`
- CSRF protection cho tất cả POST requests
- Validation input data
- Authorization checks

## Performance
- Eager loading relationships
- Pagination cho danh sách wishlist
- AJAX calls để tránh reload trang
- Caching wishlist count

## Responsive Design
- Mobile-friendly layout
- Touch-friendly buttons
- Responsive grid system
- Optimized for all screen sizes

## Browser Support
- Modern browsers (Chrome, Firefox, Safari, Edge)
- JavaScript ES6+ features
- CSS Grid và Flexbox
- AJAX với fetch API

## Dependencies
- Laravel 10+
- jQuery (cho AJAX calls)
- SweetAlert2 (cho notifications)
- Bootstrap 5 (cho styling)
- Font Awesome (cho icons)
