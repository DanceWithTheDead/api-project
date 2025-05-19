<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    const TOKEN_NAME = 'auth_token';

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    /**
     * Register a new user
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        try {

            $user = $this->authService->registerUser($request->validated());
            $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;
            \Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Пользователь успешно зарегистрирован',
                'data' => [
                    'user' => $user->only(['id', 'first_name', 'last_name', 'email']),
                    'token' => $token
                ]
            ], 201);
        }  catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при регистрации',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }


    /**
     * Login user and return token
     */
    public function login(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            throw ValidationException::withMessages([
               'email' => ['Введите корректные данные'],
            ]);
        }
        $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }


    /**
     * Logout user (revoke token)
     */
    public function logOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Вы успешно вышли'
        ]);
    }

    public function getUser(Request $request)
    {
        return response()->json([
            $request->user()
        ]);
    }
}
