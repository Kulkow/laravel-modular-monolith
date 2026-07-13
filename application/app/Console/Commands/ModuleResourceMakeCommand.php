<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ResourceMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ModuleResourceMakeCommand extends ResourceMakeCommand
{
    protected $name = 'app:module-resource';
    protected $description = 'Create a new form request class for module';

    protected function getDefaultNamespace($rootNamespace)
    {
        if ($module = $this->option('module')) {
            return $rootNamespace . '\\Modules\\' . $module . '\Http\Resources';
        }
        return $rootNamespace.'\Http\Resources';
    }


    protected function getOptions()
    {
        return [
            ['module', null, InputOption::VALUE_REQUIRED, 'The module name'],
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the resource already exists'],
            ['collection', 'c', InputOption::VALUE_NONE, 'Create a resource collection'],
        ];
    }

}
