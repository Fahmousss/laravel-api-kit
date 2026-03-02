<?php

declare(strict_types=1);

namespace App\Console\Commands\Make;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(name: 'make:model')]
class MakeModelCommand extends ModelMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:model';

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');
        $name = str_replace('/', '\\', $name);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        $parts = explode('\\', $name);

        if (count($parts) > 1) {
            $domain = array_shift($parts);
            return $rootNamespace . 'Infrastructure\\' . $domain . '\\Models\\' . implode('\\', $parts);
        }

        return $rootNamespace . 'Infrastructure\\Shared\\Models\\' . $name;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('pivot')) {
            return $this->resolveStubPath('/stubs/model.pivot.stub');
        }

        if ($this->option('morph-pivot')) {
            return $this->resolveStubPath('/stubs/model.morph-pivot.stub');
        }

        return base_path('stubs/domain/model.stub');
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        $customFactoryImport = '';
        $customUseFactory = '';

        if ($this->option('factory') || $this->option('all')) {
            $modelPath = Str::of($this->argument('name'))->studly()->replace('/', '\\')->toString();
            $factoryNamespace = 'Database\\Factories\\' . $modelPath . 'Factory';
            $factoryBasename = class_basename($factoryNamespace);
            
            $customFactoryImport = "use {$factoryNamespace};\nuse Illuminate\\Database\\Eloquent\\Attributes\\UseFactory;";
            $customUseFactory = "#[UseFactory({$factoryBasename}::class)]";
        }

        $stub = str_replace('{{ customFactoryImport }}', $customFactoryImport, $stub);
        return str_replace('{{ customUseFactory }}', $customUseFactory, $stub);
    }
}
