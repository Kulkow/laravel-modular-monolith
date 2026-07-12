<?php

namespace App\Modules\Core\Presentation\DTO\Auth;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: "Login Response",
    description: "Authentication success response"
)]
class LoginResponse
{
    #[OA\Property(
        description: "User unique identifier",
        example: "550e8400-e29b-41d4-a716-446655440000",
        format: "uuid"
    )]
    public string $userId;

    #[OA\Property(
        description: "User email address",
        example: "john.doe@example.com",
        format: "email"
    )]
    public string $email;

    #[OA\Property(
        description: "JWT access token",
        example: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
    )]
    public string $token;

    #[OA\Property(
        description: "Token lifetime in seconds",
        example: 3600,
        minimum: 1
    )]
    public int $expiresIn;

    public function __construct(string $userId, string $email, string $token, int $expiresIn)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->token = $token;
        $this->expiresIn = $expiresIn;
    }
}
