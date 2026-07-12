<?php

declare(strict_types=1);

namespace App\Modules\Identity\Infrastructure\Auth;



use App\Modules\Identity\Domain\Auth\AuthService;
use App\Modules\Identity\Event\Auth\UserLoggedIn;
use App\Modules\Identity\Http\Requests\LoginRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class SessionAuthService implements AuthService
{
    public function __construct(
        private readonly Request $request,
    ) {}

    public function authenticate(string $email, string $password): array
    {
        $this->validate($email, $password);

        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            throw ValidationException::withMessages([
                'email' => __('auth.credentials_not_match'),
            ]);
        }

        $this->request->session()->regenerate();

        UserLoggedIn::dispatch(Auth::user(), ['logged_in_time' => Carbon::now()]);

        return [
            'message'        => __('Welcome!'),
            'XSRF-TOKEN'     => $this->request->session()->token(),
            'laravel_session' => $this->request->session()->getId(),
        ];
    }

    public function logout(): array
    {
        Auth::guard('web')->logout();

        $this->request->session()->invalidate();
        $this->request->session()->regenerateToken();

        return ['message' => __('Goodbye!')];
    }

    private function validate(string $email, string $password): void
    {
        $validator = validator(
            ['email' => $email, 'password' => $password],
            (new LoginRequest())->rules(),
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function autoLogin(int $userId): array
    {
        return [];
    }
}
