<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudGenerator;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudMakeCommand;

class Test extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        $dir = $name->plural();

        $translatable = $command->option('translatable');

        $path = $translatable
            ? __DIR__.'/../stubs/Tests/Dashboard/TranslatableTest.stub'
            : __DIR__.'/../stubs/Tests/Dashboard/Test.stub';

        static::put(
            base_path("tests/Feature/{$dir}/Api"),
            $name.'Test.php',
            self::qualifyContent(
                __DIR__.'/../stubs/Tests/Api/Test.stub',
                $name
            )
        );
        static::put(
            base_path("tests/Feature/{$dir}/Dashboard"),
            $name.'FilterTest.php',
            self::qualifyContent(
                __DIR__.'/../stubs/Tests/Dashboard/FilterTest.stub',
                $name
            )
        );

        static::put(
            base_path("tests/Feature/{$dir}/Dashboard"),
            $name.'Test.php',
            self::qualifyContent($path, $name)
        );
    }
}
