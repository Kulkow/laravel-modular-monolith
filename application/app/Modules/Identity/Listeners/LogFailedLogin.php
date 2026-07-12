<?php

namespace App\Modules\Identity\Listeners;

use App\Modules\Identity\Domain\Auth\Service\LoginHistoryRecorder;
use App\Modules\Identity\Domain\Auth\ValueObjects\LoginStatus;
use Illuminate\Auth\Events\Login;

class LogFailedLogin
{
    public function __construct(private LoginHistoryRecorder $recorder) {}

    public function handle(Login $event): void
    {
        $user = $event->user;
        $request = request();

        $this->recorder->record(
            $user->getAuthIdentifier(),
            $request->ip(),
            $request->userAgent(),
            LoginStatus::FAILURE
        );
    }
}
