<?php

declare(strict_types=1);

namespace App\Modules\Identity\Infrastructure\Auth;


use App\Modules\Identity\Domain\Auth\AuthService;
use App\Modules\Identity\Event\Auth\UserLoggedIn;
use App\Modules\Identity\Http\Requests\LoginRequest;
use App\Modules\Identity\Infrastructure\Persistence\User\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class TokenAuthService implements AuthService
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

        /** @var UserModel $user */
        $user = Auth::user();
        $user->load('roles');

        UserLoggedIn::dispatch($user, ['logged_in_time' => Carbon::now()]);

        $token = $user->createToken('auth_user');

        return [
            'message'    => __('Welcome!'),
            'token'      => $token->plainTextToken,
            'token_type' => 'Bearer',
        ];
    }

    public function logout(): array
    {
        $this->request->user()->currentAccessToken()->delete();

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
        $user = UserModel::findOrFail($userId);
        $token = $user->createToken('auth_user');
        return [
            'message' => __('Welcome!'),
            'token'      => $token->plainTextToken,
            'token_type' => 'Bearer',
        ];
    }
}
