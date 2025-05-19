<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Nette\Utils\RegexpException;
use function Laravel\Prompts\error;

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
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => [
                    'required',
                    'min:8',
                ],
            ], [
                'first_name.required' => 'Поле "Имя" обязательно для заполнения.',
                'last_name.required' => 'Поле "Фамилия" обязательно для заполнения.',
                'email.required' => 'Email обязателен.',
                'email.email' => 'Введите корректный email.',
                'email.unique' => 'Этот email уже занят.',
                'password.required' => 'Пароль обязателен.',
                'password.min' => 'Пароль должен быть не менее 8 символов.',
            ]);


            $user = $this->authService->registerUser($validatedData);
            $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;

            return response()->json([
                'message' => 'Пользователь успешно зарегистрирован',
                'user' => $user->only(['id', 'first_name', 'last_name', 'email']),
                'token' => $token,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
               'message' => 'Ошибка валидации',
               'error' => $e->errors()
            ],422);
        }
    }


    /**
     * Login user and return token
     */
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            throw ValidationException::withMessages([
               'email' => ['The provided credentials are incorrect.'],
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
            'message' => 'Successfully logged out'
        ]);
    }

    public function getUser(Request $request)
    {
        return response()->json([
            $request->user()
        ]);
    }
}
