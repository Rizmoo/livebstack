<?php

namespace Oxalistech\LiveBStack\commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeCrudComponent extends Command
{
    protected $signature = 'make:crud {name} {model}';
    protected $description = 'Create a new CRUD Livewire component';

    public function handle()
    {
        $name = $this->argument('name');
        $model = $this->argument('model');

        $stub = file_get_contents(__DIR__.'/../../stubs/crud-component.stub');

        $stub = str_replace(
            ['{{ class }}', '{{ model }}'],
            [$name, $model],
            $stub
        );

        $path = app_path('Http/Livewire/' . $name . '.php');

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        file_put_contents($path, $stub);

        $this->info('CRUD component created successfully.');
    }
}