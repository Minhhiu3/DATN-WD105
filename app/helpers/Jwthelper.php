<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    public static function decodeToken($token)
    {
        try {
            $secret = env('JWT_SECRET', 'your-secret'); // đặt trong .env
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }
}