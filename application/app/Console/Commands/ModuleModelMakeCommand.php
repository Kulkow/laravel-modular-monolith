<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ModuleModelMakeCommand extends ModelMakeCommand
{
    protected $name = 'app:module-model';
    protected $description = 'Create a new Eloquent model class for module';

    protected function getDefaultNamespace($rootNamespace): string
    {
        if ($module = $this->option('module')) {
            return $rootNamespace . '\\Modules\\' . $module . '\\Models';
        }

        return parent::getDefaultNamespace($rootNamespace);
    }

    protected function createMigration(): void
    {
        if (!$this->option('migration')) {
            return;
        }

        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));
        $module = $this->option('module');
        $path = $this->laravel->basePath("app/Modules/{$module}/database/migrations");
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $schema = Str::snake($module);

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => "$schema.$table",
            '--path' => "app/Modules/{$module}/database/migrations"
        ]);
    }

    protected function createResource(): void
    {
        $resource = Str::studly(class_basename($this->argument('name')));
        $module = $this->option('module');
        $this->call('app:module-resource', [
            'name' => "{$resource}Resource",
            '--module' => $module,
            '--collection'
        ]);
    }

    protected function createSeeder(): void
    {
        if (!$this->option('seed')) {
            return;
        }

        $module = $this->option('module');
        $seederName = Str::studly(class_basename($this->argument('name'))) . 'Seeder';

        $namespace = "Modules\\{$module}\\Database\\Seeders";
        $path = $this->laravel->basePath("Modules/{$module}/Migrations/Seeders");
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $this->call('make:seeder', [
            'name' => $seederName,
            '--namespace' => $namespace
        ]);
    }

    protected function createFormRequests(): void
    {
        $request = Str::studly(class_basename($this->argument('name')));
        $module = $this->option('module');
        $this->call('app:module-request', [
            'name' => "Store{$request}Request",
            '--module' => $module,
        ]);

        $this->call('app:module-request', [
            'name' => "Update{$request}Request",
            '--module' => $module,
        ]);
    }

    public function handle()
    {
        parent::handle();
        if ($this->option('only-resource')) {
            $this->createResource();
        }
    }

    //php artisan app:module-model Category --module=Ctm --migration --requests --only-resource
    protected function getOptions()
    {
        return [
            ['module', null, InputOption::VALUE_REQUIRED, 'The module name'],
            ['only-resource',null, InputOption::VALUE_NONE, 'Indicates if the generated resource'],
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, seeder, factory, policy, resource controller, and form request classes for the model'],
            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model'],
            ['factory', 'f', InputOption::VALUE_NONE, 'Create a new factory for the model'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model'],
            ['morph-pivot', null, InputOption::VALUE_NONE, 'Indicates if the generated model should be a custom polymorphic intermediate table model'],
            ['policy', null, InputOption::VALUE_NONE, 'Create a new policy for the model'],
            ['seed', 's', InputOption::VALUE_NONE, 'Create a new seeder for the model'],
            ['pivot', 'p', InputOption::VALUE_NONE, 'Indicates if the generated model should be a custom intermediate table model'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
            ['api', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be an API resource controller'],
            ['requests', 'R', InputOption::VALUE_NONE, 'Create new form request classes and use them in the resource controller'],
        ];
    }
}
