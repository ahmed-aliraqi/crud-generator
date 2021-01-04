<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudGenerator;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudMakeCommand;
use Stichoza\GoogleTranslate\GoogleTranslate;

class Migration extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        $translatable = $command->option('translatable');

        $filterStub = $translatable
            ? __DIR__.'/../stubs/translatable_migration.stub'
            : __DIR__.'/../stubs/migration.stub';

        $table = Str::of($name)->snake()->lower()->plural();

        static::put(
            database_path("migrations"),
            date('Y_m_d_His')."_create_{$table}_table.php",
            self::qualifyContent(
                $filterStub,
                $name
            )
        );
    }
}
