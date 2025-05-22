<?php

namespace App\Http\Controllers\Api;

use App\DTO\LoginDTO;
use App\DTO\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    protected $userService;

    const TOKEN_NAME = 'auth_token';

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Register a new user
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {

        $userDTO = new UserDTO(
            firstName: $request->validated('first_name'),
            lastName: $request->validated('last_name'),
            email: $request->validated('email'),
            password: $request->validated('password'),

        );

        $user = $this->userService->registerUser($userDTO);
        $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;
        \Auth::login($user);

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
    public function login(LoginRequest $request): JsonResponse
    {

        $user = $this->userService->loginUser(
            new LoginDTO(
                email: $request->validated('email'),
                password: $request->validated('password')
            )
        );

        $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
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
