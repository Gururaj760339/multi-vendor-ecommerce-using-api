<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                ],
                'role' => 'required|in:vendor,customer',
                'phone' => 'required',
                'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
            ]);

            $path = $request->file('avatar')->store('images', 'public');

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'avatar' => $path
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            if ($user) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registration Successfully!',
                    'data' => $user,
                    'token' => $token
                ], 201);
            }
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }


    public function login(Request $request)
    {
        $response = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect Password!',
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login Successfully',
                'data' => $user,
                'token' =>  $token
            ], 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout Successfully'
            ], 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }
}
