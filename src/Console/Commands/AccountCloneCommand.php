<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class AccountCloneCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:clone 
                            {name : Class (Singular), e.g admin, supervisor, customer}
                            {to : Class (Singular), e.g admin, supervisor, customer}';

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
        $files = Collection::make($this->getDirContents(base_path('/')));

        $name = Str::of($this->argument('name'));
        $to = Str::of($this->argument('to'));

        $files = $files->filter(function ($file) use ($name) {
            return Str::contains(basename($file['path']), [
                $name->lower()->singular(),
                $name->studly()->singular(),
                $name->lower()->plural(),
                $name->studly()->plural(),
                $name->kebab()->singular(),
                $name->kebab()->plural(),
            ]);
        });

        $files = $files->map(function ($file) use ($name, $to) {

            $content = $file['type'] == 'file' ? file_get_contents($file['path']) : '';

            return [
                'from' => $file['path'],
                'to' => str_replace([
                    $name->lower()->singular(),
                    $name->studly()->singular(),
                    $name->lower()->plural(),
                    $name->studly()->plural(),
                    $name->kebab()->singular(),
                    $name->kebab()->plural(),
                ], [
                    $to->lower()->singular(),
                    $to->studly()->singular(),
                    $to->lower()->plural(),
                    $to->studly()->plural(),
                    $to->kebab()->singular(),
                    $to->kebab()->plural(),
                ], $file['path']),
                'content' => str_replace([
                    $name->lower()->singular(),
                    $name->studly()->singular(),
                    $name->lower()->plural(),
                    $name->studly()->plural(),
                    $name->kebab()->singular(),
                    $name->kebab()->plural(),
                ], [
                    $to->lower()->singular(),
                    $to->studly()->singular(),
                    $to->lower()->plural(),
                    $to->studly()->plural(),
                    $to->kebab()->singular(),
                    $to->kebab()->plural(),
                ], $content) ?: null,
                'type' => $file['type'],
            ];
        });

        foreach ($files->where('type', 'dir') as $dir) {
            if (is_dir($dir['to'])) {
                continue;
            }

            mkdir($dir['to'], 0777, true);
        }

        foreach ($files->where('type', 'file') as $file) {
            if (file_exists($file['to'])) {
                continue;
            }

            file_put_contents($file['to'], $file['content']);
        }

        $this->info("Tne new account type \"$to\" has been added successfully.");
    }

    public function getDirContents($dir, &$results = array()): array
    {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (in_array($path, [
                base_path('vendor'),
                base_path('public'),
                base_path('storage'),
                base_path('bootstrap'),
            ])) {
                continue;
            }
            if (!is_dir($path)) {
                $results[] = [
                    'path' => $path,
                    'type' => is_dir($path) ? 'dir' : 'file',
                ];
            } else if ($value != "." && $value != "..") {
                $this->getDirContents($path, $results);
                $results[] = [
                    'path' => $path,
                    'type' => is_dir($path) ? 'dir' : 'file',
                ];
            }
        }

        return $results;
    }
}
