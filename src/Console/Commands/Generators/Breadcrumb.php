<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudGenerator;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudMakeCommand;

class Breadcrumb extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command)
    {
        $name = Str::of($command->argument('name'))->plural()->snake();

        $stub = __DIR__.'/../stubs/breadcrumbs.stub';

        static::put(
            base_path("routes/breadcrumbs"),
            $name.'.php',
            self::qualifyContent(
                $stub,
                $name
            )
        );
    }
}
