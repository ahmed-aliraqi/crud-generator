<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands;

use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class Modifier
{
    public static function routes($name)
    {
        $pattern = '\/\*  The routes of generated crud will set here: Don\'t remove this line  \*\/';

        $dashboard = file_get_contents(base_path('routes/dashboard.php'));

        $api = file_get_contents(base_path('routes/api.php'));

        $controllerName = Str::of($name)->singular()->studly().'Controller';

        $resource = Str::of($name)->plural()->snake();

        $singular = $resource->singular();
        $studly = $resource->plural()->studly();

        $dashboardRoute = <<<DASHBOARD
// $studly Routes.
Route::get('trashed/$resource', '$controllerName@trashed')->name('$resource.trashed');
Route::get('trashed/$resource/{trashed_$singular}', '$controllerName@showTrashed')->name('$resource.trashed.show');
Route::post('$resource/{trashed_$singular}/restore', '$controllerName@restore')->name('$resource.restore');
Route::delete('$resource/{trashed_$singular}/forceDelete', '$controllerName@forceDelete')->name('$resource.forceDelete');
Route::resource('$resource', '$controllerName');

/*  The routes of generated crud will set here: Don't remove this line  */
DASHBOARD;

        $apiRoutes = <<<API
// $studly Routes.
Route::apiResource('$resource', '$controllerName');
Route::get('/select/$resource', '$controllerName@select')->name('$resource.select');

/*  The routes of generated crud will set here: Don't remove this line  */
API;
        $dashboard = preg_replace("/$pattern/", $dashboardRoute, $dashboard);

        $api = preg_replace("/$pattern/", $apiRoutes, $api);

        file_put_contents(base_path('routes/dashboard.php'), $dashboard);

        file_put_contents(base_path('routes/api.php'), $api);
    }

    public function sidebar($name)
    {
        $pattern = '\{\{-- The sidebar of generated crud will set here: Don\'t remove this line --\}\}';

        $resource = Str::of($name)->plural()->snake();

        $sidebarFile = file_get_contents(resource_path('views/layouts/sidebar.blade.php'));

        $sidebar = <<<SIDEBAR
@include('dashboard.$resource.partials.actions.sidebar')
{{-- The sidebar of generated crud will set here: Don't remove this line --}}
SIDEBAR;

        $sidebarFile = preg_replace("/$pattern/", $sidebar, $sidebarFile);

        file_put_contents(resource_path('views/layouts/sidebar.blade.php'), $sidebarFile);
    }

    public function permission($name)
    {
        $resource = Str::of($name)->plural()->snake();

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::updateOrCreate(['name' => "manage.$resource"]);

        $permissions = @json_decode(file_get_contents(storage_path('permissions.json'))) ?? [];

        $permissions[] = "manage.$resource";

        file_put_contents(storage_path('permissions.json'), json_encode($permissions, JSON_PRETTY_PRINT));
    }

    public function softDeletes($name)
    {
        $resource = Str::of($name)->singular()->snake();

        $map = @json_decode(file_get_contents(storage_path('soft_deletes_route_binding.json')), true) ?? [];

        $map["trashed_$resource"] = "App\\Models\\".Str::of($name)->singular()->studly();

        file_put_contents(storage_path('soft_deletes_route_binding.json'), json_encode($map, JSON_PRETTY_PRINT));
    }

    public function seeder($name)
    {
        $resource = Str::of($name)->singular()->studly();

        $pattern = '\/\*  The seeders of generated crud will set here: Don\'t remove this line  \*\/';

        $seederFile = file_get_contents(database_path('seeds/DummyDataSeeder.php'));

        $seeder = <<<SEEDER
\$this->call({$resource}Seeder::class);
        /*  The seeders of generated crud will set here: Don't remove this line  */
SEEDER;

        $seederFile = preg_replace("/$pattern/", $seeder, $seederFile);

        file_put_contents(database_path('seeds/DummyDataSeeder.php'), $seederFile);
    }

    public function langGenerator($name)
    {
        $resource = Str::of($name)->plural()->snake();

        $pattern = '\/\*  The lang of generated crud will set here: Don\'t remove this line  \*\/';

        $configFile = file_get_contents(config_path('lang-generator.php'));

        $lang = <<<LANG
'$resource' => resource_path('lang/{lang}/$resource.php'),
        /*  The lang of generated crud will set here: Don't remove this line  */
LANG;

        $configFile = preg_replace("/$pattern/", $lang, $configFile);

        file_put_contents(config_path('lang-generator.php'), $configFile);
    }
}
