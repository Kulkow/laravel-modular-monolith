<?php

namespace App\Modules\Identity\Infrastructure\Persistence\LoginHistory;

use App\Modules\Identity\Domain\Auth\Entity\LoginHistory;
use App\Modules\Identity\Domain\Auth\ValueObjects\IpAddress;
use App\Modules\Identity\Domain\Auth\ValueObjects\LoginStatus;
use App\Modules\Identity\Domain\Auth\ValueObjects\UserAgent;

class LoginHistoryMapper
{
    public static function toDomain(LoginHistoryModel $model) : LoginHistory
    {
        return new LoginHistory(
            $model->user_id,
            new IpAddress($model->ip),
            new UserAgent($model->user_agent),
            LoginStatus::from($model->status),
            $model->id,
            new \DateTimeImmutable($model->created_at)
        );
    }
}
