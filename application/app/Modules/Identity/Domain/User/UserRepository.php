<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\User;

use App\Modules\Identity\Domain\Role\RoleId;

interface UserRepository
{
    public function findById(UserId $id): ?User;

    public function findByRoleId(RoleId $id): array;

    public function findByEmail(UserEmail $email): ?User;
    public function changePassword(UserId $id, UserPassword $password): ?User;

    /** @return User[] */
    public function findAll(): array;

    public function save(User $user): UserId;

    public function delete(UserId $id): void;
}
