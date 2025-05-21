<?php

namespace App\Services;

use App\DTO\LoginDTO;
use App\DTO\UserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function registerUser(UserDTO $userDTO): User
    {
        return User::create([
            'first_name' => $userDTO->firstName,
            'last_name' => $userDTO->lastName,
            'email' => $userDTO->email,
            'password' => Hash::make($userDTO->password),
        ]);
    }


    public function loginUser(LoginDTO $loginDTO): User
    {
        $user = User::where('email', $loginDTO->email)->first();

        $this->validateCredentials($user, $loginDTO->password);

        return $user;
    }

    protected function validateCredentials(?User $user, string $password): void
    {
        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

    }

    public function updateUser(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        return $user->fresh();

    }
}
