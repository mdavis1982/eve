<?php

namespace App\Console\Commands\Eve;

use Storage;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class Currency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:currency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get latest currency rates from fixer.io';

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
        $this->line('Gathering daily currency rates');



        $client = new \GuzzleHttp\Client();

        $response = json_decode(
            $client->get(
                'http://api.fixer.io/latest?base=GBP'
            )->getBody(),
            true
        );

        Storage::disk('resources')->put(
            'data/currency.json',
            json_encode($response, JSON_PRETTY_PRINT)
        );
    }
}
