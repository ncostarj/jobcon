<?php

namespace App\Console\Commands;

use App\Models\Escala;
use App\Models\EscalaIntegrante;
use App\Models\Imports\EscalaImport;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportarEscala extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'escala:importar {data? : Data no formato Y-m-d}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Importa a escala a partir de arquivo XLS';

	private const MINIMUM_ROWS = 2;
	private const MARKER = 'x';
	private const SKIP_VALUE = [null, '-', ''];

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	private function log(string $message = "")
	{
		$hoje = date('Y-m-d H:i:s');
		$this->info("[{$hoje}] {$message}");
	}

	private function limparDadosAntigos(): void
	{
		$this->log("Apagando os dados antigos da escala.");
		DB::table('escalas_integrantes')->delete();
		DB::table('escalas')->delete();
	}

	private function isLinhaValida($linha)
	{
		// 	return !empty(array_filter($linha));
		$valores = array_filter($linha, fn($valor) => !in_array($valor, self::SKIP_VALUE));
		return count($valores) > 2;
	}

	private function getValorTratado($coluna): string
	{
		return is_numeric($coluna) ? Date::excelToDateTimeObject($coluna)->format('Y-m-d') : $coluna ?? '';
	}

	private function processarLinhas(array $linhas, array $lineHeader, array $columnHeader, $dataRef)
	{
		$lista = [];

		foreach ($linhas as $i => $linha) {

			if (empty(array_filter($linha))) {
				continue;
			}

			if (!$this->isLinhaValida($linha)) {
				continue;
			}

			if ($i % 100 == 0) {
				$this->log("Andamento [$i]");
			}

			$data = $this->getValorTratado($linha[0]);

			$this->processarColunas($linha, $lineHeader, $columnHeader, $data, $lista);
		}

		return $this->processarTimes($lista);
	}

	private function processarColunas(array $linha, array $lineHeader, array $columnHeader, string $data, array &$lista)
	{
		foreach ($linha as $j => $coluna) {

			if ($coluna !== self::MARKER) {
				continue;
			}

			$lista[$data]['escalados'][] = [
				'nome' => $lineHeader[$j],
				'time' => $columnHeader[$j]
			];

			$times = explode("/", $columnHeader[$j]);

			$this->contabilizarTimes($times, $data, $lista,  $columnHeader[$j]);
		}
	}

	private function contabilizarTimes(array $times, string $data, array &$lista, string $time)
	{
		if (count($times) > 1) {
			foreach ($times as $time) {
				$lista[$data]['times'][$time] = ($lista[$data]['times'][$time] ?? 0) + 1;
			}
			return;
		}

		$lista[$data]['times'][$time] = ($lista[$data]['times'][$time] ?? 0) + 1;
	}

	private function processarTimes(array &$lista): array
	{
		foreach ($lista as $data => $item) {
			$keyMaiorTime = array_search(max($item['times']), $item['times']);
			$lista[$data]['qtd_do_dia'] = count($item['escalados']);
			$lista[$data]['dia_equipe'] = $item['times'][$keyMaiorTime] > 4;
			$lista[$data]['equipe'] = $item['times'][$keyMaiorTime] > 4 ? $keyMaiorTime : null;
		}
		return $lista;
	}

	private function salvarDados(array $lista): void
	{
		$this->log('Salvando os dados da escala');
		foreach ($lista as $data => $item) {
			$escala = Escala::firstOrCreate([
				'dia' => $data
			], [
				'qtd_do_dia' => $item['qtd_do_dia'],
				'dia_equipe' => $item['dia_equipe'],
				'equipe' => $item['equipe']
			]);

			foreach ($item['escalados'] as $escalado) {
				EscalaIntegrante::updateOrCreate([
					'nome' => $escalado['nome'],
					'escala_id' => $escala->id
				], [
					'nome' => $escalado['nome'],
				]);
			}
		}
	}


	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		try {
			$this->log("Início da importação da escala.");

			$this->limparDadosAntigos();

			$dataRef = $this->argument('data') ?? '2025-06-27';
			$dataYmd = str_replace('-', '', $dataRef);
			$arquivo = public_path("xls/escala_fintools_{$dataYmd}.xlsx");
			$linhas = Excel::toArray(new EscalaImport, $arquivo)[0];

			$this->log("Processando o arquivo xls da escala do dia {$dataRef}.");

			$lista = $this->processarLinhas($linhas, $linhas[0], $linhas[1], $dataRef);

			$this->salvarDados($lista);

			DB::commit();

			$this->log("Fim da importação da escala.");

			return Command::SUCCESS;
		} catch (\Throwable $th) {
			DB::rollBack();
			$this->error("Erro linha: {$th->getLine()}");
			$this->error("Erro ao importar escala: {$th->getMessage()}");
			return Command::FAILURE;
		}
	}
}
