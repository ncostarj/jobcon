<?php

namespace App\Console\Commands;

use App\Models\Csv\PontoCsv;
use Illuminate\Console\Command;

class PontoImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importar:ponto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar csv das entradas do ponto';

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

		$this->info('Início');
		// $arquivo = public_path("exports/horarios_export_2024-10-09_163314.csv");
		$arquivo = public_path("exports/horarios_export_2024-10-17_230719.csv");
		$pontoCsv = new PontoCsv($arquivo);
        $saida = $pontoCsv
            ->openFile()
            ->read();
		$this->info($saida);
		$this->info('Fim');
        // return 0;
    }
}
