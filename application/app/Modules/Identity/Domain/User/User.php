<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\User;

use App\Modules\Identity\Domain\Event\UserDeactivated;
use App\Modules\Identity\Domain\Event\UserEmployeeAssigned;
use App\Modules\Identity\Domain\Event\UserEmployeeRevoked;
use App\Modules\Identity\Domain\Event\UserRoleAssigned;
use App\Modules\Identity\Domain\Event\UserRoleRevoked;
use App\Modules\Identity\Domain\Role\Role;
use App\Modules\Identity\Domain\Role\RoleId;
use App\Modules\Personnel\Domain\Employee\Employee;
use App\Modules\Personnel\Domain\Employee\EmployeeId;

final class User
{
    private array $domainEvents = [];

    /** @param Role[] $roles */
    private function __construct(
        private readonly ?UserId    $id,
        private UserEmail           $email,
        private string              $name,
        private ?UserPassword        $password,
        private UserStatus          $status,
        private array               $roles,
        private readonly \DateTimeImmutable $createdAt,
    ) {}

    public static function create(
        UserEmail    $email,
        string       $name,
        UserPassword $password,
    ): self {
        return new self(
            id:        null,
            email:     $email,
            name:      $name,
            password:  $password,
            status:    UserStatus::active(),
            roles:     [],
            createdAt: new \DateTimeImmutable(),
        );
    }

    public static function restore(
        UserId       $id,
        UserEmail    $email,
        string       $name,
        ?UserPassword $password,
        UserStatus   $status,
        array        $roles,
        \DateTimeImmutable $createdAt,
    ): self {
        return new self(
            id:        $id,
            email:     $email,
            name:      $name,
            password:  $password,
            status:    $status,
            roles:     $roles,
            createdAt: $createdAt,
        );
    }



    public function assignRole(Role $role): void
    {
        if ($this->hasRole($role->getId())) {
            return;
        }

        $this->roles[] = $role;

        $this->recordEvent(new UserRoleAssigned(
            userId:     $this->id?->getValue(),
            roleId:     $role->getId()->getValue(),
            roleName:   $role->getName()->getValue(),
            occurredAt: new \DateTimeImmutable(),
        ));
    }

    public function revokeRole(RoleId $roleId): void
    {
        foreach ($this->roles as $index => $role) {
            if ($role->getId()->equals($roleId)) {
                unset($this->roles[$index]);
                $this->roles = array_values($this->roles);

                $this->recordEvent(new UserRoleRevoked(
                    userId:     $this->id?->getValue(),
                    roleId:     $roleId->getValue(),
                    occurredAt: new \DateTimeImmutable(),
                ));
                return;
            }
        }
    }


    public function deactivate(): void
    {
        if (!$this->status->isActive()) {
            return;
        }

        $this->status = UserStatus::inactive();

        $this->recordEvent(new UserDeactivated(
            userId:     $this->id?->getValue(),
            occurredAt: new \DateTimeImmutable(),
        ));
    }

    public function block(): void
    {
        $this->status = UserStatus::blocked();
    }

    public function activate(): void
    {
        $this->status = UserStatus::active();
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    public function changeEmail(UserEmail $email): void
    {
        $this->email = $email;
    }

    public function getId(): ?UserId
    {
        return $this->id;
    }

    public function getEmail(): UserEmail
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): UserPassword
    {
        return $this->password;
    }

    public function getStatus(): UserStatus
    {
        return $this->status;
    }

    /** @return Role[] */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function hasRole(RoleId $roleId): bool
    {
        foreach ($this->roles as $role) {
            if ($role->getId()->equals($roleId)) {
                return true;
            }
        }
        return false;
    }

    public function pullEvents(): array
    {
        $events            = $this->domainEvents;
        $this->domainEvents = [];
        return $events;
    }

    private function recordEvent(object $event): void
    {
        $this->domainEvents[] = $event;
    }
}
