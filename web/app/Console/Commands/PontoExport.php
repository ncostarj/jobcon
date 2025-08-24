<?php

namespace App\Console\Commands;

use App\Models\Csv\PontoCsv;
use Illuminate\Console\Command;

class PontoExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exportar:ponto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exportar csv das entradas do ponto';

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
		$this->info('Início da exportação dos registros do ponto');
		$arquivo = public_path("exports/horarios_export_2024-10-17_230719.csv");
		$pontoCsv = new PontoCsv($arquivo);
        $saida = $pontoCsv
            ->openFile('w')
            ->write();
		$this->info($saida);
		$this->info('Fim da exportação dos registros do ponto');
        return 0;
    }
}
