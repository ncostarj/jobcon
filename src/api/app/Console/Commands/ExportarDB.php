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
	protected $signature = 'jobcon:db_export
							{--t|tipo= : Exporta a base inteira em csv ou txt}';

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

	private function toCsv(array $tables): void
	{
		$this->info('');

		foreach ($tables as $table) {
			$nomeTabela = $table;

			// if($nomeTabela == "contracheques") {
			// 	continue;
			// }

			$registros = DB::table($nomeTabela)->get();

			if ($registros->isEmpty()) {
				continue;
			}

			$startTime = microtime(true);
			$this->info("Exportando tabela {$nomeTabela}");

			$arquivo = fopen(public_path("exports/database/csv/{$nomeTabela}.csv"), 'w');

			$csv = '';
			$cabecalho = '';

			foreach ($registros as $i => $registro) {
				$linha = '';
				foreach ($registro as $key => $value) {
					if ($i == 0) {
						$cabecalho .= "{$key};";
					}

					$linha .= "{$value};";
				}
				$cabecalho = rtrim($cabecalho, ';');
				$linha = substr($linha, 0, strlen($linha) - 1);
				$csv .= "{$linha}\n";
			}

			$csv = "{$cabecalho}\n{$csv}";

			// dump($csv);

			fwrite($arquivo, $csv);
			fclose($arquivo);

			$finishTime = microtime(true);
			$diff = $finishTime - $startTime;

			$this->info("Exportada tabela {$nomeTabela} {$diff}s");
			$this->info('');
		}
	}

	private function toTxt(array $tables): void
	{
		$startTime = microtime(true);

		$this->info('');
		$this->info("Inicio export em modo texto");
		$this->info('');

		$text = "-- Arquivo de dump da base\n\n";

		foreach ($tables as $table) {

			$registros = DB::table($table)->get();

			if ($registros->isEmpty()) {
				continue;
			}

			$text .= "-- Tabela {$table}\n\n";

			foreach ($registros as $registro) {

				$linha = [];
				foreach ($registro as $key => $value) {
					$linha[$key] = $value;
				}

				$header = implode(',', array_keys($linha));
				$values = implode(',', array_map(function ($item) {

					if (!is_numeric($item) && !is_null($item)) {
						$item = "'{$item}'";
					}

					if (is_numeric($item) && str_contains($item, '.')) {
						$item = (float) $item;
					}

					if (is_numeric($item) && !str_contains($item, '.')) {
						$item = (int) $item;
					}

					if (is_null($item) || empty($item)) {
						$item = 'null';
					}

					return $item;
				}, array_values($linha)));

				$text .= "INSERT INTO {$table} ({$header}) VALUES ({$values});\n";
			}

			$text .= "\n";
		}

		$serializable = date('ymd_his');
		$arquivo = fopen(public_path("exports/database/txt/export_{$serializable}.txt"), 'w');
		fwrite($arquivo, $text);
		fclose($arquivo);

		$finishTime = microtime(true);
		$diff = $finishTime - $startTime;

		$this->info("Fim export modo texto {$diff}s");
		$this->info('');
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$this->info('Iniciando o export de banco');

		if (empty($this->option('tipo'))) {
			$this->error("Parâmetro tipo não informado");
			$this->info('Finalizando o export de banco');
			return;
		}

		$tables = DB::table('migrations')
			->get()
			->map(fn($migration) => preg_replace('/([0-9]{4}_[0-9]{2}_[0-9]{2}_[0-9]+_create_)([a-zA-Z_]+)(_table)/', '$2', $migration->migration))
			->toArray();

		match (true) {
			$this->option('tipo') == 'csv' => $this->toCsv($tables),
			$this->option('tipo') == 'txt' => $this->toTxt($tables)
		};

		$this->info('Finalizando o export de banco');
	}
}
