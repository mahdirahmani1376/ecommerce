<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ApiAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('login');
        $this->middleware('guest')->only('login');
    }

    public function createToken(Request $request)
    {
        $token = $request->user()->createToken('login_token');

        return Response::json([
            'message' => 'Token created successfully',
            'token' => $token->plainTextToken,
        ]);
    }

    public function show(Request $request)
    {
        return Response::json(
            UserResource::make($request->user())
        );
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => ['required'],
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            $this->createToken($request);
        }

        return Response::json([
            'data' => 'the credentials does not match our records',
        ]);

    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        return Response::json([
            'data' => 'you have been logged out',
        ]);
    }
}
