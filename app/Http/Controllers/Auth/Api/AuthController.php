<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('login','register');
        $this->middleware('guest')->only(['login','register']);
    }

    public function createToken(Request $request)
    {
        $request->user()->tokens()->delete();
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
            return $this->createToken($request);
        }

        return Response::json([
            'data' => 'the credentials does not match our records',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();

        return Response::json([
            'data' => 'you have been logged out',
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
            'name' => ['required','string'],
//            'password_confirmation' => ['required','confirmed']
        ]);

        $userExistence = User::where([
            'email' => $validated['email'],
            'password' => $validated['password']
        ])->exists();

        if ($userExistence){
            return Response::json([
                'data' => 'there is already a user registered with this email address',
            ]);
        }
        $user = User::create([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'name' => $validated['name'],
        ]);

        event(new Registered($user));

        return Response::json([
            'data' => 'user have been registered successfully',
        ]);
    }
}
