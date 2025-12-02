<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\text;

class CrudMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {name? : Class (Singular), e.g User, Place, Car}';

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
        $name = $this->argument('name') ?? text('What is the CRUD name?');

        $generator = new \LaravelModules\ModuleGenerator\Generator;

        $crud = $generator
            ->crud(name: $name)
            ->fromPath(__DIR__.'/../../../stubs/crud')
            ->toPath(base_path())
            ->appendToFile(
                file: resource_path('views/layouts/sidebar.blade.php'),
                content: "@include('dashboard.__CRUD_KEBAB_PLURAL__.partials.actions.sidebar')",
                before: "@include('dashboard.settings.sidebar')",
            )
            ->appendToFile(
                file: database_path('seeders/DummyDataSeeder.php'),
                content: '$this->call(__CRUD_STUDLY_SINGULAR__Seeder::class);',
                before: 'The seeders of generated crud',
            );

        $translateToArabic = confirm(
            label: 'Do you want to translate CRUD to Arabic?',
            default: false
        );

        if ($translateToArabic) {
            $singular1 = text('Enter the Arabic singular name with “ال”, e.g. القسم');
            $singular2 = text('Enter the Arabic singular name without “ال”, e.g. قسم');
            $plural1 = text('Enter the Arabic plural name with “ال”, e.g. الأقسام');
            $plural2 = text('Enter the Arabic plural name without “ال”, e.g. أقسام', 'أقسام');
        }

        $features = multiselect('Select the features you want to include', [
            'media' => 'Media Support',
            'translatable' => 'Translatable Fields',
        ]);

        $crud->publish();


        app(Modifier::class)->permission($name);

        app(Modifier::class)->softDeletes($name);

        $langPath = 'simple';

        // Simple
        if (empty($features)) {
            $generator
                ->crud(name: $name)
                ->fromPath(__DIR__.'/../../../stubs/simple')
                ->toPath(base_path())
                ->publish();
        }

        // Media Support
        if (in_array('media', $features) && ! in_array('translatable', $features)) {
            $generator
                ->crud(name: $name)
                ->fromPath(__DIR__.'/../../../stubs/media')
                ->toPath(base_path())
                ->publish();

            $langPath = 'media';
        }

        // Translatable Support
        if (! in_array('media', $features) && in_array('translatable', $features)) {
            $generator
                ->crud(name: $name)
                ->fromPath(__DIR__.'/../../../stubs/translatable')
                ->toPath(base_path())
                ->publish();

            $langPath = 'translatable';
        }

        // Media & Translatable Support
        if (in_array('media', $features) && in_array('translatable', $features)) {
            $generator
                ->crud(name: $name)
                ->fromPath(__DIR__.'/../../../stubs/translatable_media')
                ->toPath(base_path())
                ->publish();

            $langPath = 'translatable_media';
        }

        $generator
            ->crud(name: $name)
            ->fromPath(__DIR__."/../../../stubs/$langPath/lang/en")
            ->toPath(base_path('lang/en'))
            ->publish();

        if ($translateToArabic) {
            $generator
                ->crud(name: $name)
                ->fromPath(__DIR__."/../../../stubs/$langPath/lang/ar")
                ->toPath(base_path('lang/ar'))
                ->appendReplacements([
                    '__AR_SINGULAR1__' => $singular1,
                    '__AR_SINGULAR2__' => $singular2,
                    '__AR_PLURAL1__' => $plural1,
                    '__AR_PLURAL2__' => $plural2,
                ])
                ->publish();
        } else {
            $generator
                ->crud(name: $name)
                ->fromPath(__DIR__."/../../../stubs/$langPath/lang/en")
                ->toPath(base_path('lang/ar'))
                ->publish();
        }


        $this->info('Api Crud for '.$name.' created successfully 🎉');
        $this->warn('Please run "composer dump-autoload && php artisan migrate');

        return Command::SUCCESS;
    }
}
