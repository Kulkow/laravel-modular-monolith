<?php


namespace App\Modules\Identity\Domain\Auth\ValueObjects;

use InvalidArgumentException;

class IpAddress
{
    private string $value;

    public function __construct(string $ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new InvalidArgumentException("Invalid IP address: {$ip}");
        }
        $this->value = $ip;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
