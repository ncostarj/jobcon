<?php

namespace App\Console\Commands;

use App\Models\Escala;
use App\Models\EscalaIntegrante;
use App\Models\Imports\EscalaImport;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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
		$sheet = Excel::toArray(new EscalaImport, public_path('xls/escala_fintools.xlsx'));
<<<<<<< Updated upstream
		$hoje = date('Y-m-d', strtotime('now'));

		$header1 = $sheet[0][0];
		$header2 = $sheet[0][1];

		// dump($header1, $header2);
		dump(implode("|", $header1));

		foreach($sheet[0] as $i => $linhas) {

			if($i < 2) {
				continue;
			}

			$qtdColunas = count($linhas);
			$qtdColunasNulas = 0;
			$anterior = false;

			foreach($linhas as $j => $colunas) {

				$linhas[$j] = trim($colunas);

				if(is_null($colunas) || $colunas == "-" || empty($colunas)) {
					$qtdColunasNulas++;
				}

				if(is_numeric($linhas[$j])) {
					$data  = Date::excelToDateTimeObject($linhas[$j])->format('Y-m-d');;
					$linhas[$j] = $data;

					if($data < $hoje) {
						$anterior = true;
					}

				}
			}

			if($anterior) {
				continue;
			}

			if(($qtdColunas == $qtdColunasNulas) || (($qtdColunas-2) == $qtdColunasNulas)) {
				continue;
			}

			// $lista[$linhas[0]] = [
			// 	'pessoas' => []
			// ];

			foreach($linhas as $j => $colunas) {

				if($colunas == 'x') {

					$lista[$linhas[0]]['pessoas'][] = [
						'nome' => $header1[$j],
						'time' => $header2[$j]
					];
					$lista[$linhas[0]]['times'][$header2[$j]] ??= 0;
					$lista[$linhas[0]]['times'][$header2[$j]] += 1;
				}

			}

			// dump(implode('|', $linhas));
			// dump($linhas);
			// dump("{$qtdColunas}|{$qtdColunasNulas}");
			// dd($linhas);

			// if($i % 2 == 0) {
			// 	break;
			// }
		}

		foreach($lista as $data => $item) {
			$soUmTime = count($lista[$data]['times']) == 1;
			$lista[$data]['qtd_do_dia'] = count($item['pessoas']);
			$lista[$data]['dia_equipe'] = $soUmTime;
			$lista[$data]['equipe'] = $soUmTime ? Arr::first(array_keys($lista[$data]['times'])) : null;
		}

		foreach($lista as $data => $item) {
			$escala = Escala::where('dia', $data)->first();

			if(empty($escala)) {
				$escala = Escala::create([
					'dia' => $data,
					'qtd_do_dia' => $item['qtd_do_dia'],
					'dia_equipe' => $item['dia_equipe'],
					'equipe' => $item['equipe']
				]);
			}

			foreach($item['pessoas'] as $pessoa) {

				$integrante = EscalaIntegrante::where([
					[ 'nome', '=', $pessoa['nome'] ],
					[ 'escala_id', '=', $escala->id ]
				])->first();

				if(empty($integrante)) {
					$integrante = (new EscalaIntegrante)
						->fill([ 'nome' => $pessoa['nome'] ]);

					$integrante
						->escala()
						->associate($escala);

					$integrante->save();
				} else {
					$integrante
						->fill([ 'nome' => $pessoa['nome'] ])
						->save();
				}

			}
		}

		$this->info("Escala importada com sucesso");
        // return 0;
=======
		$header = [];
		$lines = [];

		foreach($sheet[0] as $i => $row) {
			// $array[] = $row;

			if($i < 2) {
				$header[] = $row;
				continue;
			}

			$qtdNull = 0;
			$qtdColunas = count($row);
			foreach($row as $j => $column) {
				$c = trim($column);
				$row[$j] = in_array($c, ['Time','Total do dia']) ? '' : $c;
				// if(empty($row[$j])) {
				// 	unset($row[$j]);
				// }
				if(is_null($column)) { $qtdNull++; }

				if(is_numeric($column)) {
					$row[$j] = Date::excelToDateTimeObject($column)->format('Y-m-d');
				}
			}

			if($qtdNull == $qtdColunas) {
				continue;
			}

			dump(implode('|',$row)."\n");
			// dump($row);

			if($i == 7){ break;}
		}

>>>>>>> Stashed changes
    }
}
