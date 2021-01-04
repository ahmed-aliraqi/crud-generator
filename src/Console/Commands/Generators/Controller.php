<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudGenerator;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudMakeCommand;

class Controller extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        $namespace = Str::of($name)->plural()->studly();

        $hasMedia = $command->option('has-media');

        // SelectController
        static::put(
            app_path("Http/Controllers/{$namespace}"),
            'SelectController.php',
            self::qualifyContent(
                __DIR__.'/../stubs/Controllers/SelectController.stub',
                $name
            )
        );

        // ApiController
        static::put(
            app_path("Http/Controllers/{$namespace}/Api"),
            $name.'Controller.php',
            self::qualifyContent(
                __DIR__.'/../stubs/Controllers/Api/Controller.stub',
                $name
            )
        );

        $stub = $hasMedia
            ? __DIR__.'/../stubs/Controllers/Dashboard/MediaController.stub'
            : __DIR__.'/../stubs/Controllers/Dashboard/Controller.stub';

        // DashboardController
        static::put(
            app_path("Http/Controllers/{$namespace}/Dashboard"),
            $name.'Controller.php',
            self::qualifyContent(
                $stub,
                $name
            )
        );
    }
}
