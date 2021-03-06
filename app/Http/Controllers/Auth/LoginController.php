<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginCheck(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        try {

            if($validator->fails()) {
                return response()->json(['status' => 0, 'message' => implode(", ", $validator->messages()->all())], 200);
            }

            if($token = JWTAuth::attempt(['email' => $request->email, 'password' => $request->password])) {

                $data = [
                    "access_token" => $token,
                    "user" => $this->guard()->user(),
                    "token_type" => "bearer",
                    "expires_in" => auth("api")->factory()->getTTL() * 60
                ];
                
                return response()->json(['status' => 1, 'message' => 'You logged in successfully!', 'data' => $data]);
            }

            return response()->json(['status' => 0, 'message' => 'Invalid username or password. Please try again.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => $e->getMessage()]);
        }
    }
}
