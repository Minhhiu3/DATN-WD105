<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $user = Auth::user();
        $wishlistItems = $user->wishlistProducts()
            ->with(['category', 'brand', 'albumProducts'])
            ->paginate(12);

        return view('client.pages.wishlist', compact('wishlistItems'));
    }

    /**
     * Add a product to wishlist.
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id_product'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        // Check if product is already in wishlist
        if (Wishlist::isInWishlist($userId, $productId)) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm đã có trong danh sách yêu thích'
            ], 400);
        }

        // Add to wishlist
        Wishlist::addToWishlist($userId, $productId);

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào danh sách yêu thích',
            'wishlist_count' => Auth::user()->wishlists()->count()
        ]);
    }

    /**
     * Remove a product from wishlist.
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id_product'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        // Remove from wishlist
        $removed = Wishlist::removeFromWishlist($userId, $productId);

        if ($removed) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích',
                'wishlist_count' => Auth::user()->wishlists()->count()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy sản phẩm trong danh sách yêu thích'
        ], 404);
    }

    /**
     * Toggle wishlist status for a product.
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id_product'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        if (Wishlist::isInWishlist($userId, $productId)) {
            // Remove from wishlist
            Wishlist::removeFromWishlist($userId, $productId);
            $isInWishlist = false;
            $message = 'Đã xóa sản phẩm khỏi danh sách yêu thích';
        } else {
            // Add to wishlist
            Wishlist::addToWishlist($userId, $productId);
            $isInWishlist = true;
            $message = 'Đã thêm sản phẩm vào danh sách yêu thích';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_in_wishlist' => $isInWishlist,
            'wishlist_count' => Auth::user()->wishlists()->count()
        ]);
    }

    /**
     * Check if a product is in wishlist.
     */
    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id_product'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        $isInWishlist = Wishlist::isInWishlist($userId, $productId);

        return response()->json([
            'success' => true,
            'is_in_wishlist' => $isInWishlist
        ]);
    }

    /**
     * Get wishlist count for the authenticated user.
     */
    public function count(): JsonResponse
    {
        $count = Auth::user()->wishlists()->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Clear all items from wishlist.
     */
    public function clear(): JsonResponse
    {
        $userId = Auth::id();
        $deleted = Wishlist::where('user_id', $userId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa tất cả sản phẩm khỏi danh sách yêu thích',
            'deleted_count' => $deleted
        ]);
    }
}
