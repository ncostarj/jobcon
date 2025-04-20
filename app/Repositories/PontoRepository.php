<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Ponto;
use App\Models\Horario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PontoRepository
{
	protected $model;

	public function __construct() {
		$this->model = Ponto::class;
	}

	public function get(array $data = [])
	{
		extract($data);

		$ordenacao = empty($ordenacao) ? 'desc': $ordenacao;

		return $this->model::with('horarios')
			->where([
				[ 'user_id', '=', $usuario_id ]
			])
			->when($mes, function($query, $mes){
				return $query->whereRaw('MONTH(dia) = ?', [ $mes ]);
			})
			->when($ordenacao, function($query, $ordenacao) {
				return $query->orderBy('dia', $ordenacao);
			})
			->get();
	}

	public function insert(array $data)
	{
		$ponto = $this->model::query()->where('dia', $data['dia'])->first();

		if (empty($ponto)) {
			$ponto = new Ponto;
			$pontoData = [
				'dia' => $data['dia'],
				'categoria' => $data['categoria'],
			];

			if(!empty($data['pedir_ajuste'])) {
				$pontoData = array_merge($data, [
					'pedir_ajuste' => $data['pedir_ajuste'],
				]);
			}

			if(!empty($data['observacao'])) {
				$pontoData = array_merge($data, [
					'observacao' => $data['observacao']
				]);
			}

			$ponto->fill($pontoData);

			$ponto->usuario()->associate(User::where('id', $data['usuario_id'])->first());

			$ponto->save();

			$horario = Horario::query()
				->where('ponto_id', $ponto->id)
				->where('hora', $data['hora'])
				->first();

			if (empty($horario)) {
				$horario = (new Horario)
					->fill([
						"hora" => $data['hora'],
						"tipo" => $data['tipo'],
						// "observacao" => $data['observacao']
					]);

				$ponto->horarios()->save($horario);
			}
		}

		if (!empty($ponto)) {

			$pontoData = [];

			if(!empty($data['pedir_ajuste'])) {
				$pontoData = array_merge($data, [
					'pedir_ajuste' => $data['pedir_ajuste'],
				]);
			}

			if(!empty($data['observacao_dia'])) {
				$pontoData = array_merge($data, [
					'observacao' => $data['observacao_dia']
				]);
			}

			if(!empty($pontoData)) {
				$ponto
					->fill($pontoData)
					->save();
			}

			$horario = Horario::query()
				->where('ponto_id', $ponto->id)
				->where('tipo', $data['tipo'])
				->first();

			if (empty($horario)) {

				$horarioData = [
					"hora" => $data['hora'],
					"tipo" => $data['tipo'],
				];

				if(!empty($data['observacao_horario'])) {
					$horarioData = array_merge($horarioData, [
						"observacao" => $data['observacao_horario']
					]);
				}

				$horario = (new Horario)
					->fill($horarioData);

				$ponto->horarios()->save($horario);
			}
		}

		return $ponto;
	}

	public function update() {}

	public function delete() {}

	public function searchMonths() {
		return Ponto::query()
			->selectRaw('date_format(dia, "%m") as mes, date_format(dia, "%Y") as ano')
			->groupBy(DB::raw('date_format(dia, "%m"), date_format(dia, "%Y")'))
			->orderBy('ano', 'desc')
			->orderBy('mes', 'desc')
			->get();
	}

	public function summarize($dados) {

		$mes = isset($dados['mes']) ? $dados['mes'] : date('m');

		$pontos = Ponto::query()
			// ->where([
			// 	// ['dia', '>=', $firstDay],
			// 	// ['dia', '<=', $lastDay],
			// ])
			->whereRaw('MONTH(dia) = ?', [ $mes ])
			->get();

		$summarize = [];
		foreach($pontos as $ponto) {
			$summarize[$ponto->categoria] ??= 0;
			$summarize[$ponto->categoria] += 1;

			$summarize['ajustes'] ??= 0;
			if($ponto->pedir_ajuste) {
				$summarize['ajustes']+=1;
			}

			$summarize['observacoes'] ??= 0;
			if(!empty($ponto->observacao)) {
                $summarize['observacoes']+=1;
            }

			$summarize['total'] ??= 0;
			$summarize['total'] += 1;

		}

		return $summarize;
	}
}
