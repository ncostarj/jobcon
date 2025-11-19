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
    protected $signature = 'jobcon:db_import
							{--t|tipo= : Importa a base a partir de um arquivo csv ou txt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Script para exportar a base de dados inteira do projeto.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

	private function fromCsv() {
		$migrations = DB::table('migrations')->get();

		$tabelas = [];

		foreach($migrations as $migration) {
			// 2014_10_12_000000_create_users_table
			$nomeTabela = preg_replace("/([0-9]{4}_[0-9]{2}_[0-9]{2}_[0-9]+_create_)(.*)(_table)/",'$2',$migration->migration);

			if(in_array($nomeTabela, [
				'roles','role_user','actions','action_role','audit',
				"password_resets",
				"failed_jobs",
				"personal_access_tokens",
				"roles",
				"bancos",
				"escalas",
				"escalas_integrantes"
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
	}

	private function fromTxt() {
		$migrations = DB::table('migrations')->get();

		$tabelas = [];
	}

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$this->info('Iniciando o import de banco');

		match (true) {
			$this->option('tipo') == 'csv' => $this->fromCsv(),
			$this->option('tipo') == 'txt' => $this->fromTxt()
		};

		$this->info('Finalizando o import de banco');
    }
}
