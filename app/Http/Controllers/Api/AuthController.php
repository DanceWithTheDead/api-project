<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $user = $this->authService->registerUser($request->validated());
        $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;
        \Auth::login($user);

        //Email Message

        return (new UserResource($user))->additional([
            'message' => 'User created successfully.',
            'token' => $token,
        ])
            ->response()
            ->setStatusCode(201);
    }


    /**
     * Login user and return token
     */
    public function login(Request $request): JsonResponse
    {
        /*Upgrade method and add chek user active or not
         * and send on email message*/

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
            'message' => 'Logged out successfully.',
        ]);
    }

}
