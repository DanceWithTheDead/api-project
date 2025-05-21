<?php

namespace App\DTO;

class UserDTO
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $password,
    )
    {
    }
}
