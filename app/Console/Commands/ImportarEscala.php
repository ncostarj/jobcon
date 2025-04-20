<?php

namespace App\Console\Commands;

use App\Models\Imports\EscalaImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportarEscala extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'escala:importar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para importar a escala xls';

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
		$linhas = Excel::toArray(new EscalaImport, public_path('xls/escala_fintools.xlsx'));
		$contador = 0;
		foreach($linhas as $linha) {
			foreach($linha as $l) {
				dump($l);
				if($contador == 25) {break;}
				$contador++;
			}
			if($contador == 25) {break;}
		}
        // return 0;
    }
}
