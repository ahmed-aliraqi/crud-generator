<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudGenerator;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudMakeCommand;

class Model extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        $translatable = $command->option('translatable');

        $hasMedia = $command->option('has-media');


        if ($translatable) {
            static::put(
                app_path("Models/Translations"),
                $name.'Translation.php',
                self::qualifyContent(
                    __DIR__.'/../stubs/Model/Translations/Translation.stub',
                    $name
                )
            );
        }

        if ($translatable && $hasMedia) {
            static::put(
                app_path("Models"),
                $name.'.php',
                self::qualifyContent(
                    __DIR__.'/../stubs/Model/TranslatableMediaModel.stub',
                    $name
                )
            );
        } elseif ($translatable && ! $hasMedia) {
            static::put(
                app_path("Models"),
                $name.'.php',
                self::qualifyContent(
                    __DIR__.'/../stubs/Model/TranslatableModel.stub',
                    $name
                )
            );
        } elseif (! $translatable && $hasMedia) {
            static::put(
                app_path("Models"),
                $name.'.php',
                self::qualifyContent(
                    __DIR__.'/../stubs/Model/MediaModel.stub',
                    $name
                )
            );
        } elseif (! $translatable && ! $hasMedia) {
            static::put(
                app_path("Models"),
                $name.'.php',
                self::qualifyContent(
                    __DIR__.'/../stubs/Model/Model.stub',
                    $name
                )
            );
        }
    }
}
