<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class CrudGenerator
{
    /**
     * @param $name
     * This will create model from stub file
     */
    public static function model($name)
    {
        $template = str_replace(
            ['{{modelName}}'],
            [$name],
            static::getStubs('Model')
        );

        file_put_contents(app_path("/{$name}.php"), $template);
    }

    /**
     * @param $name
     * This will create Request from stub file
     */
    public static function request($name)
    {
        $template = str_replace(
            ['{{modelName}}'],
            [$name],
            static::getStubs('Request')
        );

        if (! file_exists($path = app_path('/Http/Requests/'))) {
            mkdir($path, 0777, true);
        }

        file_put_contents(app_path("/Http/Requests/{$name}Request.php"), $template);
    }

    /**
     * @param $name
     * This will create migration using artisan command
     */
    public static function migration($name)
    {
        Artisan::call('make:migration create_'.strtolower(Str::plural($name)).'_table --create='.strtolower(Str::plural($name)));
    }

    /**
     * @param $name
     * This will create route in api.php file
     */
    public static function route($name)
    {
        $path_to_file = base_path('routes/api.php');
        $append_route = 'Route::apiResource(\''.Str::plural(strtolower($name))."', '{$name}Controller'); \n";
        File::append($path_to_file, $append_route);
    }

    protected static function qualifyContent($stub, $name)
    {
        return str_replace(
            [
                '{{studlySingular}}',
                '{{studlyPlural}}',
                '{{lowercaseSingular}}',
                '{{lowercasePlural}}',
                '{{lowercaseDisplayPlural}}',
                '{{lowercaseDisplaySingular}}',
                '{{uppercaseDisplayPlural}}',
                '{{uppercaseDisplaySingular}}',
            ],
            [
                $studlySingular = Str::of($name)->singular()->studly(),
                $studlyPlural = Str::of($name)->plural()->studly(),
                $lowercaseSingular = Str::of($name)->snake()->singular()->lower(),
                $lowercasePlural = Str::of($name)->snake()->plural()->lower(),
                $lowercaseDisplayPlural = Str::of($name)->snake()->replace('_', ' ')->plural()->lower(),
                $lowercaseDisplaySingular = Str::of($name)->snake()->replace('_', ' ')->singular()->lower(),
                $uppercaseDisplayPlural = Str::of($name)->snake()->replace('_', ' ')->ucfirst()->plural()->lower(),
                $uppercaseDisplaySingular = Str::of($name)->snake()->replace('_', ' ')->ucfirst()->singular()->lower(),
            ],
            file_get_contents($stub)
        );
    }

    protected static function put($path, $file, $content)
    {
        if (! is_dir($path)) {
            // dir doesn't exist, make it
            mkdir($path, 0777, true);
        }

        if (file_exists($path.'/'.$file)) {
            return;
        }

        file_put_contents($path.'/'.$file, $content);
    }
}
