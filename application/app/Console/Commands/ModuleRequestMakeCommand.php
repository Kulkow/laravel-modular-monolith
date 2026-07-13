<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\RequestMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ModuleRequestMakeCommand extends RequestMakeCommand
{
    protected $name = 'app:module-request';
    protected $description = 'Create a new form request class for module';

    protected function getDefaultNamespace($rootNamespace): string
    {
        if ($module = $this->option('module')) {
            return $rootNamespace . '\\Modules\\' . $module . '\Http\Requests';
        }
        return $rootNamespace.'\Http\Requests';
    }



    protected function getOptions(): array
    {
        return [
            ['module', null, InputOption::VALUE_REQUIRED, 'The module name'],
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the request already exists'],
        ];
    }
}
