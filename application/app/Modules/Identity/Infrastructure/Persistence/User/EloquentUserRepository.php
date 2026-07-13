<?php

declare(strict_types=1);

namespace App\Modules\Identity\Infrastructure\Persistence\User;


use App\Modules\Identity\Domain\Role\RoleId;
use App\Modules\Identity\Domain\User\User;
use App\Modules\Identity\Domain\User\UserEmail;
use App\Modules\Identity\Domain\User\UserId;
use App\Modules\Identity\Domain\User\UserPassword;
use App\Modules\Identity\Domain\User\UserRepository;
use Illuminate\Support\Facades\Hash;

final class EloquentUserRepository implements UserRepository
{
    public function findById(UserId $id): ?User
    {
        $model = UserModel::query()
            ->with(['roles.permissions'])
            ->find($id->getValue());

        return $model ? UserMapper::toDomain($model) : null;
    }

    public function findByEmail(UserEmail $email): ?User
    {
        $model = UserModel::query()
            ->with(['roles.permissions'])
            ->where('email', $email->getValue())
            ->first();

        return $model ? UserMapper::toDomain($model) : null;
    }

    public function findAll(): array
    {
        return UserModel::query()
            ->with(['roles.permissions'])
            ->get()
            ->map(fn(UserModel $model) => UserMapper::toDomain($model))
            ->all();
    }

    public function save(User $user): UserId
    {
        if ($user->getId() === null) {
            $model = new UserModel();
            $password = $user->getPassword()->getValue();
            $model->password = $password;
        } else {
            $model = UserModel::query()->findOrFail($user->getId()->getValue());
        }

        $model->email     = $user->getEmail()->getValue();
        $model->name      = $user->getName();
        $model->status_id = $user->getStatus()->getId();
        $model->save();

        $roleNames = array_map(
            fn($role) => $role->getName()->getValue(),
            $user->getRoles()
        );
        $model->syncRoles($roleNames);

        return new UserId($model->id);
    }



    public function delete(UserId $id): void
    {
        UserModel::query()->where('id', $id->getValue())->delete();
    }

    public function findByRoleId(RoleId $id): array
    {
        $users = UserModel::whereHas('roles', function($query) use ($id) {
            $query->where('id', $id->getValue());
        })->get();
        return $users
            ->map(fn(UserModel $model) => UserMapper::toDomain($model))
            ->all();
    }

    public function changePassword(UserId $id, UserPassword $password): ?User
    {
        $user = UserModel::query()->findOrFail($id->getValue());
        if(! $user){
            throw new \Exception('User not found '.$id->getValue());
        }
        $user->update([
            'password' => Hash::make($password->getValue()),
        ]);
        return UserMapper::toDomain($user);
    }
}
