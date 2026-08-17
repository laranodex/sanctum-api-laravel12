<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIs\User\SignInRequest;
use App\Http\Requests\APIs\User\SignUpRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends Controller
{
    public function signUp(SignUpRequest $request)
    {
        $data = $request->validated();

        User::create($data);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully!',
            'data' => $data,
        ], 200);
    }

    public function signIn(SignInRequest $request)
    {
        $data = $request->validated();

        if (!Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        } else {

            $user = Auth::user();

            $token = $user->createToken("auth_token")->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login successful.',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 200);
        }
    }

    public function signOut(Request $request)
    {
        $user = $request->user();

        $user->tokens("auth_token")->delete();

        return response()->json([
            'status' => true,
            'message' => "You Sign Out Successfully!",
            'user' => $user
        ], 200);
    }
}
