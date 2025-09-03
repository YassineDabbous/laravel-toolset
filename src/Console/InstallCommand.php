<?php

namespace Ysn\SuperCore\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'superset:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the qarya resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        // $this->comment('Publishing Qarya Assets...');
        // $this->callSilent('vendor:publish', ['--tag' => 'horizon-assets']);

        $this->comment('Publishing Qarya Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'qarya-config', '--force' => true]);

        $this->info('Qarya plateform installed successfully.');
    }

}
