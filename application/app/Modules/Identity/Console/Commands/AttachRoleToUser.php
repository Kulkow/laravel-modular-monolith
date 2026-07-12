<?php
namespace App\Modules\Identity\Console\Commands;


use App\Modules\Identity\Application\Role\AssignRole\AssignRoleByNameDto;
use App\Modules\Identity\Application\Role\AssignRole\AssignRoleByNameUseCase;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class AttachRoleToUser extends Command
{
    protected $signature = 'user:attach-role {email : Email пользователя} {role : Имя роли}';

    protected $description = 'Назначить пользователю роль';

    public function __construct(private AssignRoleByNameUseCase $useCase)
    {
        parent::__construct();
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'email' => 'Укажите Email пользователя',
            'role' => 'Укажите Имя роли',
        ];
    }

    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->argument('role');
        $dto = new AssignRoleByNameDto($email, $role);
        $this->useCase->execute($dto);
        $this->info('Роль назначена пользователю');
        return CommandAlias::SUCCESS;
    }
}
