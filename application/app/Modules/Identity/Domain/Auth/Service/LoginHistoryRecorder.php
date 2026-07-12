<?php

namespace App\Modules\Identity\Domain\Auth\Service;

use App\Modules\Identity\Domain\Auth\Entity\LoginHistory;
use App\Modules\Identity\Domain\Auth\Repository\LoginHistoryRepositoryInterface;
use App\Modules\Identity\Domain\Auth\ValueObjects\IpAddress;
use App\Modules\Identity\Domain\Auth\ValueObjects\LoginStatus;
use App\Modules\Identity\Domain\Auth\ValueObjects\UserAgent;

class LoginHistoryRecorder
{
    public function __construct(
        private LoginHistoryRepositoryInterface $repository
    ) {}

    public function record(
        int $userId,
        string $ip,
        string $userAgent,
        LoginStatus $status
    ): void {
        $history = new LoginHistory(
            $userId,
            new IpAddress($ip),
            new UserAgent($userAgent),
            $status
        );
        $this->repository->save($history);
    }
}
