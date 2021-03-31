<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            if(!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'Wrong credentials',
                    'error' => 'Invalid email or password',
                ], 422);
            }
        } catch (Exception $error) {
            return response()->json([
                'message' => 'Error in Login',
                'error' => $error,
            ], 500);
        }
    }
}
