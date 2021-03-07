<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudGenerator;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudMakeCommand;

class Filter extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        $namespace = Str::of($name)->plural()->studly();

        $translatable = $command->option('translatable');

        $filterStub = $translatable
            ? __DIR__.'/../stubs/Filters/TranslatableFilter.stub'
            : __DIR__.'/../stubs/Filters/Filter.stub';

        static::put(
            app_path("Http/Filters"),
            $name.'Filter.php',
            self::qualifyContent(
                $filterStub,
                $name
            )
        );
    }
}
