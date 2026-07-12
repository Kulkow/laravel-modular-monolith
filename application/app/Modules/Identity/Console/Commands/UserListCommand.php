<?php
namespace App\Modules\Identity\Console\Commands;


use App\Modules\Identity\Application\User\ListUsers\ListUsersUseCase;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class UserListCommand extends Command
{
    protected $signature = 'user:list';

    protected $description = 'Список пользователей';

    public function __construct(private readonly ListUsersUseCase $listUsers)
    {
        parent::__construct();
    }



    public function handle()
    {
        $list = $this->listUsers->execute();
        foreach ($list as $user) {
            $this->info('Пользователь '.$user->email.' - '.$user->name);
            foreach ($user->roles as $role) {
                $this->info('Роль - '.$role->name);
            }
            $this->info('------');
        }

        return CommandAlias::SUCCESS;
    }
}
