<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudGenerator;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudMakeCommand;
use Stichoza\GoogleTranslate\GoogleTranslate;

class View extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command)
    {
        $name = Str::of($command->argument('name'))->plural()->snake();

        $translatable = $command->option('translatable');

        $hasMedia = $command->option('has-media');

        if ($translatable && $hasMedia) {
            $stubPath = __DIR__.'/../stubs/Views/translatable_has_media';
        } elseif ($translatable && ! $hasMedia) {
            $stubPath = __DIR__.'/../stubs/Views/translatable';
        } elseif (! $translatable && $hasMedia) {
            $stubPath = __DIR__.'/../stubs/Views/has_media';
        } else {
            $stubPath = __DIR__.'/../stubs/Views/default';
        }

        // Actions
        static::put(
            resource_path("views/dashboard/{$name}/partials/actions"),
            'create.blade.php',
            self::qualifyContent(
                $stubPath.'/partials/actions/create.blade.stub',
                $name
            )
        );
        static::put(
            resource_path("views/dashboard/{$name}/partials/actions"),
            'delete.blade.php',
            self::qualifyContent(
                $stubPath.'/partials/actions/delete.blade.stub',
                $name
            )
        );
        static::put(
            resource_path("views/dashboard/{$name}/partials/actions"),
            'edit.blade.php',
            self::qualifyContent(
                $stubPath.'/partials/actions/edit.blade.stub',
                $name
            )
        );
        static::put(
            resource_path("views/dashboard/{$name}/partials/actions"),
            'forceDelete.blade.php',
            self::qualifyContent(
                $stubPath.'/partials/actions/forceDelete.blade.stub',
                $name
            )
        );
        static::put(
            resource_path("views/dashboard/{$name}/partials/actions"),
            'link.blade.php',
            self::qualifyContent(
                $stubPath.'/partials/actions/link.blade.stub',
                $name
            )
        );
        static::put(
            resource_path("views/dashboard/{$name}/partials/actions"),
            'restore.blade.php',
            self::qualifyContent(
                $stubPath.'/partials/actions/restore.blade.stub',
                $name
            )
        );
        static::put(
            resource_path("views/dashboard/{$name}/partials/actions"),
            'trashed.blade.php',
            self::qualifyContent(
                $stubPath.'/partials/actions/trashed.blade.stub',
                $name
            )
        );
        static::put(
            resource_path("views/dashboard/{$name}/partials/actions"),
            'show.blade.php',
            self::qualifyContent(
                $stubPath.'/partials/actions/show.blade.stub',
                $name
            )
        );
        static::put(
            resource_path("views/dashboard/{$name}/partials/actions"),
            'sidebar.blade.php',
            self::qualifyContent(
                $stubPath.'/partials/actions/sidebar.blade.stub',
                $name
            )
        );
        // Partials
        static::put(
            resource_path("views/dashboard/{$name}/partials"),
            'filter.blade.php',
            self::qualifyContent(
                $stubPath.'/partials/filter.blade.stub',
                $name
            )
        );
        static::put(
            resource_path("views/dashboard/{$name}/partials"),
            'form.blade.php',
            self::qualifyContent(
                $stubPath.'/partials/form.blade.stub',
                $name
            )
        );
        // Resource
        static::put(
            resource_path("views/dashboard/{$name}"),
            'create.blade.php',
            self::qualifyContent(
                $stubPath.'/create.blade.stub',
                $name
            )
        );
        static::put(
            resource_path("views/dashboard/{$name}"),
            'edit.blade.php',
            self::qualifyContent(
                $stubPath.'/edit.blade.stub',
                $name
            )
        );
        static::put(
            resource_path("views/dashboard/{$name}"),
            'index.blade.php',
            self::qualifyContent(
                $stubPath.'/index.blade.stub',
                $name
            )
        );
        static::put(
            resource_path("views/dashboard/{$name}"),
            'show.blade.php',
            self::qualifyContent(
                $stubPath.'/show.blade.stub',
                $name
            )
        );
        static::put(
            resource_path("views/dashboard/{$name}"),
            'trashed.blade.php',
            self::qualifyContent(
                $stubPath.'/trashed.blade.stub',
                $name
            )
        );
    }
}
