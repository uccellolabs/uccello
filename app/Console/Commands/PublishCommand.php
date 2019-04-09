<?php

namespace Uccello\Core\Console\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uccello:publish {--force : Overwrite any existing files} {--views : Publish Uccello views}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all of the Uccello resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Uccello Assets...');
        $this->call('vendor:publish', [
            '--tag' => 'uccello-assets',
            '--force' => true,
        ]);

        $this->comment('Publishing Uccello Configuration...');
        $this->call('vendor:publish', [
            '--tag' => 'uccello-config',
            '--force' => $this->option('force'),
        ]);

        if ($this->option('views') === true) {
            $this->comment('Publishing Uccello Views...');
            $this->call('vendor:publish', [
                '--tag' => 'uccello-views',
                '--force' => $this->option('force'),
            ]);
        }
    }
}