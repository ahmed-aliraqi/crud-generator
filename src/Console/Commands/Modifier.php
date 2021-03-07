<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands;

use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class Modifier
{
    public static function routes($name)
    {
        $pattern = '\/\*  The routes of generated crud will set here: Don\'t remove this line  \*\/';

        $place = '/*  The routes of generated crud will set here: Don\'t remove this line  */';

        $dashboard = file_get_contents(base_path('routes/dashboard.php'));

        $api = file_get_contents(base_path('routes/api.php'));

        $controllerName = Str::of($name)->singular()->studly().'Controller';

        $resource = Str::of($name)->plural()->snake();

        $dashboardRoute = "Route::resource('$resource', '$controllerName');\n$place";
        $apiRoutes = "Route::apiResource('$resource', '$controllerName');\nRoute::get('/select/$resource', '$controllerName@select')->name('{$resource}.select');\n$place";

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

    public function seeder($name)
    {
        $resource = Str::of($name)->singular()->studly();

        $pattern = '\/\*  The seeders of generated crud will set here: Don\'t remove this line  \*\/';

        $place = '/*  The seeders of generated crud will set here: Don\'t remove this line  */';

        $seederFile = file_get_contents(database_path('seeds/DummyDataSeeder.php'));

        $seeder = "\$this->call({$resource}Seeder::class);\n$place";

        $seederFile = preg_replace("/$pattern/", $seeder, $seederFile);

        file_put_contents(database_path('seeds/DummyDataSeeder.php'), $seederFile);
    }
}
