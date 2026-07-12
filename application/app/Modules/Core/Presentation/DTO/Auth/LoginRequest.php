<?php

namespace App\Modules\Core\Presentation\DTO\Auth;

use OpenApi\Attributes as OA;


#[OA\Schema(
    title: "Login Request",
    description: "User credentials for authentication",
    required: ["email", "password"]
)]
class LoginRequest
{
    #[OA\Property(
        description: "User email address",
        example: "john.doe@example.com",
        format: "email"
    )]
    public string $email;

    #[OA\Property(
        description: "User password (minimum 8 characters)",
        example: "SecurePass123!",
        format: "password",
        minLength: 8
    )]
    public string $password;
}
