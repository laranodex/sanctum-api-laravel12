<?php

namespace App\Http\Controllers\APIs;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\APIs\User\SignInRequest;
use App\Http\Requests\APIs\User\SignUpRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function signUp(SignUpRequest $request)
    {
        $data = $request->validated();

        $user = User::create($data);

        return ApiResponse::success(
            'User created successfully!',
            $user,
            201
        );
    }

    public function signIn(SignInRequest $request)
    {
        $data = $request->validated();

        if (!Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ])) {
            return ApiResponse::error(
                'Invalid email or password.',
                null,
                401
            );
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success(
            'Login successful.',
            [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ]
        );
    }

    public function signOut(Request $request)
    {
        $user = $request->user();

        // Delete current token only
        $user->currentAccessToken()->delete();

        return ApiResponse::success(
            'You signed out successfully!',
            null
        );
    }
}
