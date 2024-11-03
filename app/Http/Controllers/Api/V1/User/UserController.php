<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function getBakery(): JsonResponse
    {
        Log::info('getBakery');
        $user = Auth::user();

        if ($user->bakery()->exists()) {
            return response()->json($user->bakery, 200);
        }

        return response()->json([], 200);
    }
}
