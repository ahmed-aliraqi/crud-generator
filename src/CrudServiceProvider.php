<?php

namespace AhmedAliraqi\CrudGenerator;

use AhmedAliraqi\CrudGenerator\Console\Commands\AccountCloneCommand;
use Illuminate\Support\ServiceProvider;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudMakeCommand;

class CrudServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom($configPath = __DIR__.'/../config/crud-generator.php', 'crud-generator');

        $this->publishes([$configPath => config_path('crud-generator.php')]);

        $this->commands([
            CrudMakeCommand::class,
            AccountCloneCommand::class,
        ]);
    }
}
