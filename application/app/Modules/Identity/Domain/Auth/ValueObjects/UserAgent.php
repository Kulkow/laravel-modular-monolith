<?php


namespace App\Modules\Identity\Domain\Auth\ValueObjects;

class UserAgent
{
    private string $value;

    public function __construct(string $userAgent)
    {
        // можно добавить обрезку до 255 символов для БД
        $this->value = $userAgent ?: 'unknown';
    }

    public function value(): string
    {
        return $this->value;
    }
}
