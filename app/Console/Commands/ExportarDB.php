<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportarDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bd:exportar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para exportar o banco de dados inteiro';

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
		$this->info('Inicio');
		$this->info('');

		$tables = DB::select('SHOW TABLES');
		foreach($tables as $table)
		{
			$nomeTabela = $table->Tables_in_jobcon;
			$registros = DB::table($nomeTabela)->get();

			if($registros->isEmpty()) {
				continue;
			}

			$startTime = microtime(true);
			$this->info("Exportado tabela {$nomeTabela}");

			$arquivo = fopen(public_path("exports/database/{$nomeTabela}.csv"),'w');

			$csv = '';
			$cabecalho = '';

			foreach($registros as $i => $registro) {
				$linha = '';
				foreach($registro as $key => $value) {
					if($i == 0) {
						$cabecalho .= "{$key};";
					}

					$linha .= "{$value};";
				}
				$cabecalho = rtrim($cabecalho, ';');
				$linha = substr($linha,0,strlen($linha)-1);
				// $tamCabecalho = count(explode(';',$cabecalho));
				// $tamLinha = count(explode(';',$linha));

				// dump("{$tamCabecalho}|{$tamLinha}");

				// if($tamCabecalho!=$tamLinha) {
					// dd('algo errado aqui', $tamCabecalho, $tamLinha);
				// }

				$csv .= "{$linha}\n";
			}

			$csv = "{$cabecalho}\n{$csv}";

			// dump($csv);

			fwrite($arquivo, $csv);
			fclose($arquivo);

			$finishTime = microtime(true);
			$diff = $finishTime - $startTime;

			$this->info("Tabela {$nomeTabela} exportada com sucesso {$diff}s");
			$this->info('');
		}
		$this->info('Fim');
    }
}
