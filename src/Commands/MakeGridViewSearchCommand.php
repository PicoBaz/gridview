<?php

namespace Picobaz\GridView\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeGridViewSearchCommand extends GeneratorCommand
{
    protected $signature = 'make:gridview-search {name : The name of the search model}';
    protected $description = 'Create a new GridView search model class';
    protected $type = 'SearchModel';

    protected function getStub()
    {
        return __DIR__ . '/stubs/search-model.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\SearchModel';
    }

    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);
        $modelClass = $this->guessModelClass($class);
        $stub = str_replace(['{{ class }}', '{{ modelClass }}'], [$class, $modelClass], $stub);
        return $stub;
    }

    protected function guessModelClass($searchClass)
    {
        $modelName = str_replace('Search', '', $searchClass);
        return $this->rootNamespace() . 'Models\\' . $modelName;
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . '.php';
    }
}