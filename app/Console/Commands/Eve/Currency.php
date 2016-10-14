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

        Storage::disk('resources')->put('data/rates.json', 'Contents');

        // $response = json_decode(
        //     $this->client->get(sprintf(
        //         "http://api.fixer.io/latest"
        //     ))->getBody(),
        //     true
        // );

        // return $response['data'] ? $response['data']['images']['downsized']['url'] : null;
    }
}
