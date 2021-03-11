<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use AhmedAliraqi\CrudGenerator\Console\Commands\Generators\Lang;
use AhmedAliraqi\CrudGenerator\Console\Commands\Generators\Test;
use AhmedAliraqi\CrudGenerator\Console\Commands\Generators\View;
use AhmedAliraqi\CrudGenerator\Console\Commands\Generators\Model;
use AhmedAliraqi\CrudGenerator\Console\Commands\Generators\Filter;
use AhmedAliraqi\CrudGenerator\Console\Commands\Generators\Policy;
use AhmedAliraqi\CrudGenerator\Console\Commands\Generators\Seeder;
use AhmedAliraqi\CrudGenerator\Console\Commands\Generators\Factory;
use AhmedAliraqi\CrudGenerator\Console\Commands\Generators\Request;
use AhmedAliraqi\CrudGenerator\Console\Commands\Generators\Resource;
use AhmedAliraqi\CrudGenerator\Console\Commands\Generators\Migration;
use AhmedAliraqi\CrudGenerator\Console\Commands\Generators\Breadcrumb;
use AhmedAliraqi\CrudGenerator\Console\Commands\Generators\Controller;

class CrudMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud 
                            {name : Class (Singular), e.g User, Place, Car}
                            {--translatable}
                            {--has-media}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all Crud operations with a single command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Lang::generate($this);
        Breadcrumb::generate($this);
        View::generate($this);
        Resource::generate($this);
        Migration::generate($this);
        Factory::generate($this);
        Seeder::generate($this);
        Policy::generate($this);
        Controller::generate($this);
        Model::generate($this);
        Request::generate($this);
        Filter::generate($this);
        Test::generate($this);

        $name = $this->argument('name');

        app(Modifier::class)->routes($name);

        app(Modifier::class)->sidebar($name);

        app(Modifier::class)->seeder($name);

        app(Modifier::class)->permission($name);

        app(Modifier::class)->softDeletes($name);

        app(Modifier::class)->langGenerator($name);

        $seederName = Str::of($name)->singular()->studly().'Seeder';

        $this->info('Api Crud for '.$name.' created successfully 🎉');
        $this->warn('Please run "composer dump-autoload && php artisan migrate && php artisan db:seed --class='.$seederName.'"');
    }
}
