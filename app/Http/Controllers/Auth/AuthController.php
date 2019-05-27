<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }

    public function user(Request $request)
    {
        try {
            $user = auth('api')->user();

            if(!$user) {
                return response()->json([
                    'status'    => 0, 
                    'message'   => 'unauthorized'
                ]);
            }

            return response()->json([
                'status' => 1,
                'data' => [
                    'user' => $user
                ]
            ]);

        } catch(\Exception $e) {
            return response()->json([
                'status'    => 0, 
                'message'   => $e->getMessage()
            ]);
        }        
    }

    public function logout() 
    {
        Auth::logout();
        
        return response()->json([
            'status' => 1,
            'message' => 'Successfully logged out'            
        ]);
    }
}
