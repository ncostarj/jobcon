<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportarDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bd:importar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para importar o database';

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

		$migrations = DB::table('migrations')->get();

		$tabelas = [];

		foreach($migrations as $migration) {
			// 2014_10_12_000000_create_users_table
			$nomeTabela = preg_replace("/([0-9]{4}_[0-9]{2}_[0-9]{2}_[0-9]+_create_)(.*)(_table)/",'$2',$migration->migration);

			if(in_array($nomeTabela, [
				"password_resets",
				"failed_jobs",
				"personal_access_tokens",
				"roles",
				"bancos"
			])) {
				continue;
			}

			$tabelas[] = $nomeTabela;
		}

		foreach($tabelas as $tabela) {

			// if(in_array($tabela,['users','ferias', 'pontos', 'horarios','frequencias','empresas','contracheques'])) {
			// 	continue;
			// }

			// DB::table($tabela)->truncate();
			$registros = [];
			$startTime = microtime(true);
			$this->info("{$tabela}");
			$arquivoCsv = fopen(public_path("exports/database/{$tabela}.csv"),'r');
			$contador = 0;

			while ($row = fgetcsv($arquivoCsv, 1000, ";")) {

				if($contador == 0){
					$header = $row;
					$contador++;
					continue;
				}

				if(count($header) != count($row)) {
					dd($header, $row);
				}

				// dd($header, $row);
				$linha = array_combine($header, $row);
				foreach($linha as $key => $value) {
					$linha[$key] = $value!=""? $value : null;
				}

				$registros[] = $linha;
				$contador++;
			}
			fclose($arquivoCsv);

			DB::table($tabela)->insert($registros);
			$finishTime = microtime(true);
			$diff = $finishTime - $startTime;
			$this->info("{$tabela} Fim {$diff}");
		}

		// if ($handle = opendir(public_path("exports/database"))) {
		// 	/* Esta é a forma correta de percorrer o diretório */
		// 	while (false !== ($arquivo = readdir($handle)))
		// 	{
		// 		if(in_array($arquivo, ['.','..'])) {
		// 			continue;
		// 		}

		// 		dump("$arquivo");
		// 		$contador = 0;
		// 		$registros = [];
		// 		$arquivoCsv = fopen(public_path("exports/database/{$arquivo}"), 'r');
		// 		while ($row = fgetcsv($arquivoCsv, 1000, ";")) {
		// 			if($contador == 0){
		// 				$header = $row;
		// 				$contador++;
		// 				continue;
		// 			}
		// 			$linha = array_combine($header, $row);
		// 			foreach($linha as $key => $value) {
		// 				$linha[$key] = $value!=""? $value : null;
		// 			}
		// 			$registros[] = $linha;
		// 			$contador++;
		// 		}
		// 		fclose($arquivoCsv);

		// 		$tabela = preg_replace("/(.*)(.csv)/","$1",$arquivo);
		// 		echo "$tabela\n";
		// 		DB::table($tabela)->insert($registros);
		// 		dd('fim tabela');
		// 	}
		// }

		// $arquivo = fopen(public_path("exports/database/{$nomeTabela}.csv"),'w');
        // return 0;
    }
}
