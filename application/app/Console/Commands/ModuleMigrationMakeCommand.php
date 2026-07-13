<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class ModuleMigrationMakeCommand extends Command
{

    protected $signature = 'app:make-migration {name} {--module=}';


    protected $description = 'make migration for Module';


    public function handle(): void
    {
        $name = $this->argument('name');
        $module = $this->option('module');
        $path = $this->laravel->basePath("app/Modules/{$module}/database/migrations");
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $this->call('make:migration', [
            'name' => $name,
            '--path' => "app/Modules/{$module}/database/migrations"
        ]);
    }

    protected function getOptions(): array
    {
        return [
            ['module', null, InputOption::VALUE_REQUIRED, 'The module name'],
        ];
    }
}
