<?php


namespace App\Modules\Identity\Domain\Auth\Entity;

use App\Modules\Identity\Domain\Auth\ValueObjects\IpAddress;
use App\Modules\Identity\Domain\Auth\ValueObjects\LoginStatus;
use App\Modules\Identity\Domain\Auth\ValueObjects\UserAgent;
use DateTimeImmutable;

class LoginHistory
{
    private ?int $id;
    private int $userId;
    private IpAddress $ip;
    private UserAgent $userAgent;
    private LoginStatus $status;
    private DateTimeImmutable $createdAt;

    public function __construct(
        int                $userId,
        IpAddress          $ip,
        UserAgent          $userAgent,
        LoginStatus        $status,
        ?int               $id = null,
        ?DateTimeImmutable $createdAt = null
    )
    {
        $this->userId = $userId;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
        $this->status = $status;
        $this->id = $id;
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
    }

    // Геттеры
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getIp(): IpAddress
    {
        return $this->ip;
    }

    public function getUserAgent(): UserAgent
    {
        return $this->userAgent;
    }

    public function getStatus(): LoginStatus
    {
        return $this->status;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
