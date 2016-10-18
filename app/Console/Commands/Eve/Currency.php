<?php

namespace App\Console\Commands\Eve;

use Storage;
use GuzzleHttp\Client;
use App\Client\FixerClient;
use Illuminate\Console\Command;

class Currency extends Command
{
    /**
     * @var string
     */
    protected $signature = 'eve:currency';

    /**
     * @var string
     */
    protected $description = 'Get latest currency rates from fixer.io';

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param  FixerClient $client
     *
     * @return mixed
     */
    public function handle(FixerClient $client)
    {
        $this->line('Gathering daily currency rates...');

        $response = $client->rates('GBP');

        if (! isset($response['rates'])) {
            $this->error('Unable to gather currency rates');

            return;
        }

        Storage::disk(getenv('DATA_PATH'))->put(
            'currency.json',
            json_encode($response, JSON_PRETTY_PRINT)
        );

        $this->line('Currency rates gathered');
    }
}
