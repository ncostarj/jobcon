<?php

namespace App\Console\Commands;

use App\Models\Time;
use Illuminate\Console\Command;
use Jenssegers\Mongodb\Queue\MongoConnector;
use MongoDB\Client;

class TestMongoClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mongodb:test_client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
		$client = (new Client(env('DB_URI')))->{'jobcon'}->{'horarios'};
		$insert = $client->insertOne([
				'dia' => '2024-07-25',
				'hora' => '08:00',
				'tipo' => 'entrada1'
			]);
		dd($insert);
    }
}
