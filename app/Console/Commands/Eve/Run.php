<?php

namespace App\Console\Commands\Eve;

use App\Bots\Eve;
use Illuminate\Console\Command;

class Run extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Eve';

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
    public function handle(Eve $eve)
    {
        $this->line('');
        $this->line('***********************************************');
        $this->line('* Eve - A Slackbot For The Larachat Community *');
        $this->line('***********************************************');
        $this->line('');
        $this->line('Connecting to Slack...');

        $eve->connect()->then(function () {
            $this->info('Connected Successfully!');
        }, function (\Exception $exception) {
            $this->error($exception->getMessage());
        });

        $eve->run();
    }
}
