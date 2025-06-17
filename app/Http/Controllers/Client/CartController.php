<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtHelper;

class CartController extends Controller
{
    public function getCartData(Request $request)
    {
        $token = $request->bearerToken(); // Lấy token từ header Authorization: Bearer xxx

        $decoded = JwtHelper::decodeToken($token);

        if (!$decoded || !isset($decoded->sub)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userId = $decoded->sub;

        $cart = session("cart_user_{$userId}", []);

        return response()->json(['success' => true, 'data' => $cart]);
    }

    public function updateCart(Request $request)
    {
        $token = $request->bearerToken();

        $decoded = JwtHelper::decodeToken($token);

        if (!$decoded || !isset($decoded->sub)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userId = $decoded->sub;
        $items = $request->input('items', []);

        session(["cart_user_{$userId}" => $items]);

        return response()->json(['success' => true, 'message' => 'Giỏ hàng đã được cập nhật']);
    }
}