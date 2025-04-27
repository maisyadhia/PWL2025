<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            // invalidate token
            $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

            if ($removeToken) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logout berhasil!',
                ]);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout gagal!',
            ], 500);
        }
    }
}
