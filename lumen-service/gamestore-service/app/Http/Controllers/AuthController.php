<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
            ],
            'password' => 'required|min:6',
            'role' => 'nullable|in:admin,developer,user'
        ]);

        $role = $request->role ?? 'user';

        if ($role === 'admin') {
            if (User::where('role', 'admin')->exists()) {
                return response()->json([
                    'message' => 'Admin already exists'
                ], 403);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role
        ]);

        return response()->json([
            'message' => 'Register success',
            'data' => $user
        ], 201);
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'error' => 'Invalid email or password'
            ], 401);
        }

        return response()->json([
            'message' => 'Login success',
            'token' => $token
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'Logout success'
        ]);
    }
}
