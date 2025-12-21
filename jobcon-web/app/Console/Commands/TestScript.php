<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestScript extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'jobcon:test_script';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Script de teste de um cenário php qualquer.';

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
		// $password = "12312323";

		$arquivo = fopen(storage_path('files/octo.sql'), 'r');

		$contador = 0;
		$linhas = [];
		$linhaTratada = '';
		$tabelas = '';
		while ($row = fgets($arquivo, 1000)) {

			if (str_contains($row, 'DROP')) {

				// if (str_contains($row, 'relatorios_3040_fundos_externos')) {
				// 	dd($row);
				// }

				$linhaTratada = preg_replace('/DROP TABLE IF EXISTS `([a-z_0-9]+)`;$/', '$1$2', $row);
			}

			if (str_contains($row, 'cnpj')) {
				$match = [];
				preg_match('/_index/', $row, $match);
				if(!empty($match)){continue;}
				$linhaTratada = rtrim($linhaTratada, "\n");
				$campo = ltrim($row);
				$tabelas .= "Tabela {$linhaTratada} - {$campo}";
			}

			$contador++;
		}

		$arquivo2 = fopen(storage_path('files/campos.txt'),'w');
		fwrite($arquivo2, $tabelas);
		fclose($arquivo2);

		return "0";
	}
}
