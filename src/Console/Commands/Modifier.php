<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class Modifier
{
    public static function routes($name)
    {
        $pattern = '\/\*  The routes of generated crud will set here: Don\'t remove this line  \*\/';

        $place = '/*  The routes of generated crud will set here: Don\'t remove this line  */';

        $dashboard = file_get_contents(base_path('routes/dashboard.php'));

        $api = file_get_contents(base_path('routes/api.php'));

        $controllerNamespace = Str::of($name)->plural()->studly();

        $controllerName = Str::of($name)->singular()->studly().'Controller';

        $resource = Str::of($name)->plural()->snake();

        $dashboardRoute = "Route::resource('$resource', '{$controllerNamespace}\Dashboard\\$controllerName');\n$place";
        $apiRoutes = "Route::apiResource('$resource', '{$controllerNamespace}\Api\\$controllerName');\nRoute::get('/select/$resource', '{$controllerNamespace}\SelectController@index')->name('{$resource}.select');\n$place";

        $dashboard = preg_replace("/$pattern/", $dashboardRoute, $dashboard);

        $api = preg_replace("/$pattern/", $apiRoutes, $api);

        file_put_contents(base_path('routes/dashboard.php'), $dashboard);

        file_put_contents(base_path('routes/api.php'), $api);
    }

    public function sidebar($name)
    {
        $pattern = '\{\{-- The sidebar of generated crud will set here: Don\'t remove this line --\}\}';

        $place = '{{-- The sidebar of generated crud will set here: Don\'t remove this line --}}';

        $resource = Str::of($name)->plural()->snake();

        $sidebarFile = file_get_contents(resource_path('views/layouts/sidebar.blade.php'));

        $sidebar = "@include('dashboard.$resource.partials.actions.sidebar')\n$place";

        $sidebarFile = preg_replace("/$pattern/", $sidebar, $sidebarFile);

        file_put_contents(resource_path('views/layouts/sidebar.blade.php'), $sidebarFile);
    }
}
