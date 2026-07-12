Структура app/Modules/Identity
Identity/
├── Domain/ # Чистая бизнес-логика, нет зависимостей от Laravel
│ ├── User/
│ │ ├── User.php # Агрегат: assignRole, revokeRole, deactivate, pullEvents
│ │ ├── UserId.php # Value Object
│ │ ├── UserEmail.php # Value Object с валидацией
│ │ ├── UserStatus.php # Value Object: ACTIVE / INACTIVE / BLOCKED
│ │ └── UserRepository.php # Интерфейс репозитория
│ ├── Role/
│ │ ├── Role.php # Агрегат: givePermission, revokePermission
│ │ ├── RoleId.php / RoleName.php
│ │ └── RoleRepository.php
│ ├── Permission/
│ │ ├── Permission.php
│ │ ├── PermissionCode.php # VO: валидирует формат 'module.action'
│ │ └── PermissionRepository.php
│ ├── Access/
│ │ ├── AccessChecker.php # Интерфейс: hasPermission, assertPermission
│ │ ├── AccessDeniedException.php
│ │ └── Permissions/ # Права по модулям (string-backed enums)
│ │ ├── CtmPermission.php # ctm.product-cards.view, ...
│ │ ├── ProductionPermission.php # production.work-orders.create, ...
│ │ ├── WarehousePermission.php # warehouse.acceptance.confirm, ...
│ │ ├── OrderPermission.php # orders.confirm, ...
│ │ ├── SupplyPermission.php # supply.purchase-orders.create, ...
│ │ └── IdentityPermission.php # identity.users.manage, ...
│ └── Event/
│ ├── UserCreated.php / UserDeactivated.php
│ └── UserRoleAssigned.php / UserRoleRevoked.php
│
├── Application/ # Use Cases — координируют домен
│ ├── DTO/
│ │ └── UserDto.php / RoleDto.php / PermissionDto.php
│ ├── User/
│ │ ├── CreateUser/ UpdateUser/ DeactivateUser/ GetUser/
│ └── Role/
│ ├── AssignRole/ RevokeRole/
│
├── Infrastructure/ # Зависит от Laravel + Spatie
│ ├── Persistence/
│ │ ├── User/ EloquentUserRepository + UserModel(HasRoles) + UserMapper
│ │ ├── Role/ EloquentRoleRepository + RoleModel extends SpatieRole
│ │ └── Permission/ EloquentPermissionRepository + PermissionModel extends SpatiePermission
│ └── Access/
│ └── SpatieAccessChecker.php # Реализует AccessChecker через Spatie
│
├── Http/
│ ├── Controllers/Api/ UserController + RoleController
│ ├── Requests/ CreateUser + UpdateUser + AssignRole
│ └── Resources/ UserResource + RoleResource
│
├── Providers/
│ └── IdentityServiceProvider.php # Биндинг интерфейсов, регистрация роутов
└── config/routes/api.php
Как использовать AccessChecker в других модулях
// В любом UseCase или Controller другого модуля

````
use App\Modules\Identity\Domain\Access\AccessChecker;
use App\Modules\Identity\Domain\Access\Permissions\ProductionPermission;
use App\Modules\Identity\Domain\User\UserId;
final readonly class CreateWorkOrderUseCase
{
public function __construct(
private AccessChecker $accessChecker,
// ...
) {}
public function execute(int $userId, ...): void
    {
        $this->accessChecker->assertPermission(
        new UserId($userId),
            ProductionPermission::CreateWorkOrder,
        );
        // throws AccessDeniedException если прав нет
    }
}
````

Что нужно сделать вручную
Зарегистрировать провайдер в config/app.php (секция providers):

````
    App\Modules\Identity\Providers\IdentityServiceProvider::class,
````

Добавить status_id в миграцию users таблицы (если её нет):

````
    $table->unsignedTinyInteger('status_id')->default(1);
````

Добавить label в миграцию roles таблицы Spatie:

````
    $table->string('label')->nullable();
````

Опубликовать конфиг Spatie и указать кастомные модели:

````
// config/permission.php
'models' => [
    'permission' => \App\Modules\Identity\Infrastructure\Persistence\Permission\PermissionModel::class,
    'role'       => \App\Modules\Identity\Infrastructure\Persistence\Role\RoleModel::class,
],
````

## Заполнить роли
````
  php artisan db:seed --class="App\Modules\Identity\Infrastructure\Seeders\RolesSeeder"
````

## Список пользователей
````
    php artisan user:list
   
````

## Назначить роль
* user:attach-role {email : Email пользователя} {role : Имя роли}
````
    php artisan user:attach-role test@test.ru admin
````

## Назначить Пароль
* user:password
  {email : Email пользователя}
  {--password= : Новый пароль (если не указан, будет сгенерирован)}
  {--show : Показать новый пароль}
  {--force : Пропустить подтверждение}
````
    php artisan user:password "admin@erp2.fpulse.org" --force --show
````
