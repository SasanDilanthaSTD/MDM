<?php

namespace App\Http\Custom;

use Illuminate\Support\Facades\Auth;

class IsAuthenticated
{
    public static function check()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }
    }
}