<?php

namespace App\Domain\Jobs\Services;

use App\Domain\Jobs\DTOs\HorarioDTO;
use App\Domain\Jobs\DTOs\PontoDTO;
use App\Domain\Jobs\Interfaces\ServiceInterface;
use App\Domain\Jobs\Models\Ponto;
use App\Domain\Jobs\Repositories\HorarioRepository;
use App\Domain\Jobs\Repositories\PontoRepository;
use Illuminate\Support\Collection;

class PontoService implements ServiceInterface
{
	protected $pontoRepository;
	protected $horarioRepository;

	public function __construct(PontoRepository $pontoRepository, HorarioRepository $horarioRepository)
	{
		$this->pontoRepository = $pontoRepository;
		$this->horarioRepository = $horarioRepository;
	}

	public function search(array $criteria = []): Collection
	{
		$teste = $this->pontoRepository->search($criteria);
		// logger($teste);
		return $teste;
	}

	public function find(string $id): ?Ponto
	{
		return $this->pontoRepository->find($id);
	}

	public function create(array $data): Ponto
	{
		return $this->pontoRepository->create($data);
	}

	public function update(string $id, array $data): bool
	{
		return $this->pontoRepository->update($id, $data);
	}

	public function delete(string $id): bool
	{
		return $this->pontoRepository->delete($id);
	}

	public function assign(array $data): Ponto
	{
		$ponto = $this->pontoRepository
			->search([
				'usuario_id' => $data['usuario_id'],
				'dia' => $data['dia']
			])->first();

		if (empty($ponto)) {
			$pontoDTO = PontoDTO::fromArray($data);
			return $this->pontoRepository->create($pontoDTO->toArray());
		}

		$horario = $this->horarioRepository->search([
			'ponto_id' => $ponto->id,
			'hora' => $data['hora']
		])->first();

		if (empty($horario)) {
			$horarioDTO = HorarioDTO::fromArray($data);
			$this->horarioRepository->create($horarioDTO->toArray());
		}

		return $ponto;

		// $ponto = $this->model::query()->where('dia', $data['dia'])->first();

		// if (empty($ponto)) {
		// 	$ponto = new Ponto;
		// 	$pontoData = [
		// 		'dia' => $data['dia'],
		// 		'categoria' => $data['categoria'],
		// 	];

		// 	if (!empty($data['pedir_ajuste'])) {
		// 		$pontoData = array_merge($data, [
		// 			'pedir_ajuste' => $data['pedir_ajuste'],
		// 		]);
		// 	}

		// 	if (!empty($data['observacao'])) {
		// 		$pontoData = array_merge($data, [
		// 			'observacao' => $data['observacao']
		// 		]);
		// 	}

		// 	$ponto->fill($pontoData);

		// 	$ponto->usuario()->associate(User::where('id', $data['usuario_id'])->first());

		// 	$ponto->save();

		// 	$horario = Horario::query()
		// 		->where('ponto_id', $ponto->id)
		// 		->where('hora', $data['hora'])
		// 		->first();

		// 	if (empty($horario)) {
		// 		$horario = (new Horario)
		// 			->fill([
		// 				"hora" => $data['hora'],
		// 				"tipo" => $data['tipo'],
		// 				// "observacao" => $data['observacao']
		// 			]);

		// 		$ponto->horarios()->save($horario);
		// 	}
		// }

		// if (!empty($ponto)) {

		// 	$pontoData = [];

		// 	if (!empty($data['pedir_ajuste'])) {
		// 		$pontoData = array_merge($data, [
		// 			'pedir_ajuste' => $data['pedir_ajuste'],
		// 		]);
		// 	}

		// 	if (!empty($data['observacao_dia'])) {
		// 		$pontoData = array_merge($data, [
		// 			'observacao' => $data['observacao_dia']
		// 		]);
		// 	}

		// 	if (!empty($pontoData)) {
		// 		$ponto
		// 			->fill($pontoData)
		// 			->save();
		// 	}

		// 	$horario = Horario::query()
		// 		->where('ponto_id', $ponto->id)
		// 		->where('tipo', $data['tipo'])
		// 		->first();

		// 	if (empty($horario)) {

		// 		$horarioData = [
		// 			"hora" => $data['hora'],
		// 			"tipo" => $data['tipo'],
		// 		];

		// 		if (!empty($data['observacao_horario'])) {
		// 			$horarioData = array_merge($horarioData, [
		// 				"observacao" => $data['observacao_horario']
		// 			]);
		// 		}

		// 		$horario = (new Horario)
		// 			->fill($horarioData);

		// 		$ponto->horarios()->save($horario);
		// 	}
		// }

		// return $ponto;


		// return Ponto::all()->first();

	}

	public function summarize(array $criteria)
	{
		return $this->pontoRepository->summarize($criteria);
	}

	public function getMonths(array $criteria): Collection
	{
		return $this->pontoRepository->getMonths($criteria);
	}

	// public function get(array $data = [])
	// {
	// 	extract($data);

	// 	$ordenacao = empty($ordenacao) ? 'desc' : $ordenacao;

	// 	return $this->model::with('horarios')
	// 		->where([
	// 			['user_id', '=', $usuario_id]
	// 		])
	// 		->when($mes, function ($query, $paramMes) {
	// 			return $query->whereRaw('MONTH(dia) = ?', [$paramMes]);
	// 		})
	// 		->when($ano, function ($query, $paramAno) {
	// 			return $query->whereRaw('YEAR(dia) = ?', [$paramAno]);
	// 		})
	// 		->when($ordenacao, function ($query, $paramOrdenacao) {
	// 			return $query->orderBy('dia', $paramOrdenacao);
	// 		})
	// 		->get();
	// }

	// public function insert(array $data)
	// {
	// 	$ponto = $this->model::query()->where('dia', $data['dia'])->first();

	// 	if (empty($ponto)) {
	// 		$ponto = new Ponto;
	// 		$pontoData = [
	// 			'dia' => $data['dia'],
	// 			'categoria' => $data['categoria'],
	// 		];

	// 		if (!empty($data['pedir_ajuste'])) {
	// 			$pontoData = array_merge($data, [
	// 				'pedir_ajuste' => $data['pedir_ajuste'],
	// 			]);
	// 		}

	// 		if (!empty($data['observacao'])) {
	// 			$pontoData = array_merge($data, [
	// 				'observacao' => $data['observacao']
	// 			]);
	// 		}

	// 		$ponto->fill($pontoData);

	// 		$ponto->usuario()->associate(User::where('id', $data['usuario_id'])->first());

	// 		$ponto->save();

	// 		$horario = Horario::query()
	// 			->where('ponto_id', $ponto->id)
	// 			->where('hora', $data['hora'])
	// 			->first();

	// 		if (empty($horario)) {
	// 			$horario = (new Horario)
	// 				->fill([
	// 					"hora" => $data['hora'],
	// 					"tipo" => $data['tipo'],
	// 					// "observacao" => $data['observacao']
	// 				]);

	// 			$ponto->horarios()->save($horario);
	// 		}
	// 	}

	// 	if (!empty($ponto)) {

	// 		$pontoData = [];

	// 		if (!empty($data['pedir_ajuste'])) {
	// 			$pontoData = array_merge($data, [
	// 				'pedir_ajuste' => $data['pedir_ajuste'],
	// 			]);
	// 		}

	// 		if (!empty($data['observacao_dia'])) {
	// 			$pontoData = array_merge($data, [
	// 				'observacao' => $data['observacao_dia']
	// 			]);
	// 		}

	// 		if (!empty($pontoData)) {
	// 			$ponto
	// 				->fill($pontoData)
	// 				->save();
	// 		}

	// 		$horario = Horario::query()
	// 			->where('ponto_id', $ponto->id)
	// 			->where('tipo', $data['tipo'])
	// 			->first();

	// 		if (empty($horario)) {

	// 			$horarioData = [
	// 				"hora" => $data['hora'],
	// 				"tipo" => $data['tipo'],
	// 			];

	// 			if (!empty($data['observacao_horario'])) {
	// 				$horarioData = array_merge($horarioData, [
	// 					"observacao" => $data['observacao_horario']
	// 				]);
	// 			}

	// 			$horario = (new Horario)
	// 				->fill($horarioData);

	// 			$ponto->horarios()->save($horario);
	// 		}
	// 	}

	// 	return $ponto;
	// }

	// public function update() {}

	// public function delete() {}

	// public function searchMonths(array $dados)
	// {
	// 	return Ponto::query()
	// 		->selectRaw('date_format(dia, "%m") as mes, date_format(dia, "%Y") as ano')
	// 		->when($dados['usuario_id'], function($query) use ($dados){
	// 			return $query->where('user_id', $dados['usuario_id']);
	// 		})
	// 		->groupBy(DB::raw('date_format(dia, "%m"), date_format(dia, "%Y")'))
	// 		->orderBy('ano', 'desc')
	// 		->orderBy('mes', 'desc')
	// 		->get();
	// }

	// public function summarize($dados)
	// {
	// 	extract($dados);

	// 	$pontos = Ponto::query()
	// 		->where('user_id', $usuario_id)
	// 		->when($mes, function ($query, $paramMes) {
	// 			return $query->whereRaw('MONTH(dia) = ?', [$paramMes]);
	// 		})
	// 		->when($ano, function ($query, $paramAno) {
	// 			return $query->whereRaw('YEAR(dia) = ?', [$paramAno]);
	// 		})
	// 		->get();

	// 	$summarize = [];
	// 	foreach ($pontos as $ponto) {
	// 		$summarize[$ponto->categoria] ??= 0;
	// 		$summarize[$ponto->categoria] += 1;

	// 		$summarize['ajustes'] ??= 0;
	// 		if ($ponto->pedir_ajuste) {
	// 			$summarize['ajustes'] += 1;
	// 		}

	// 		$summarize['observacoes'] ??= 0;
	// 		if (!empty($ponto->observacao)) {
	// 			$summarize['observacoes'] += 1;
	// 		}

	// 		$summarize['total'] ??= 0;
	// 		$summarize['total'] += 1;
	// 	}

	// 	return $summarize;
	// }


	// protected $repository;

	// public function __construct()
	// {
	// 	$this->repository = new PontoRepository;
	// }

	// public function get(array $dados = [])
	// {
	// 	return $this->defaultReponse(200, '', (new PontoResource($this->repository->get($dados)))->toArray());
	// }

	// public function assign($dados)
	// {
	// 	$response = $this->repository->insert($dados);
	// 	// $this->notifyAssign($dados);
	// 	return $this->defaultReponse(200, '', $response);
	// 	// $this->sendBotAssign($dados);
	// 	// return $this->defaultReponse(200, '', []);
	// }

	// private function sendBotAssign($dados) {
	// 	logger($dados);
	// 	$response = Http::get('http://jobconrpa:3000/efetuar-marcacao', $dados);
	// 	logger($response);
	// }

	// private function notifyAssign($dados)
	// {
	// 	// Log::info($dados);
	// 	$categoria = $dados['categoria'] == 'home_office' ? ':house:' : ':ot:';
	// 	switch($dados['tipo']) {
	// 		case 'entrada': $texto = 'Bom dia'; $icone = $categoria;break;
	// 		case 'almoco_saida': $texto = 'Almoço'; $icone = ':knife_fork_plate:';break;
	// 		case 'almoco_retorno': $texto = 'Voltei'; $icone = $categoria;break;
	// 		case 'saida':  $texto = 'Saindo';$icone = ':bed:';break;
	// 		default: $texto = '-'; $icone = ':bomba:'; break;
	// 	}

	// 	// Log::info("{$texto} {$icone}");

	// 	// (new SlackNotification())
	// 	// 	->setToken(config('slack.SLACK_BOT_USER_OAUTH_TOKEN'))
	// 	// 	->setChannel(config('slack.SLACK_BOT_USER_CHANNEL_ID'))
	// 	// 	->setMessage($texto)
	// 	// 	->setStatus($icone)
	// 	// 	->notify();
	// }

	// public function sumHours(array $dados)
	// {
	// 	$credito = strtotime("00:00");
	// 	$debito = strtotime("00:00");

	// 	// ['mes' => date('m'), 'usuario_id' => User::where('name', 'Newton Gonzaga Costa')->first()->id]
	// 	foreach ($this->repository->get($dados) as $ponto) {

	// 		logger("{$ponto->debito}|{$ponto->credito}");

	// 		if($ponto->debito != '00:00') {
	// 			$addDebito = '+';

	// 			[$hora,$minutos] = explode(':',$ponto->debito);

	// 			if($hora != '00') {
	// 				$addDebito .= " {$hora} hours";
	// 			}

	// 			if($minutos!= '00') {
	// 				$addDebito .= " {$minutos} minutes";
	// 			}

	// 			$debito = strtotime($addDebito, $debito);
	// 		}

	// 		if($ponto->credito != '00:00') {
	// 			$addCredito = '+';

	// 			[$hora,$minutos] = explode(':',$ponto->credito);

	// 			if($hora != '00') {
	// 				$addCredito .= " {$hora} hours";
	// 			}

	// 			if($minutos!= '00') {
	// 				$addCredito .= " {$minutos} minutes";
	// 			}

	// 			$credito = strtotime($addCredito, $credito);
	// 		}

	// 	}

	// 	$debito = date('H:i', $debito);
	// 	$credito = date('H:i', $credito);

	// 	logger("{$debito}|{$credito}");

	// 	return $this->defaultReponse(200, '', (object) compact('debito', 'credito'));
	// }

	// public function searchMonths(array $dados) {
	// 	$myCalendar = new MyCalendar();
	// 	$listMes = $this->repository->searchMonths($dados);

	// 	$meses = [];
	// 	foreach($listMes as $mes) {
	// 		$meses[] = [
	// 			'numero' => $mes->mes,
	// 			'nome' => $myCalendar->getMes($mes->mes),
	// 			'ano' => $mes->ano,
	// 			'mes_ano' => "{$mes->ano}-{$mes->mes}",
	// 		];
	// 	}

	// 	return $this->defaultReponse(200, '', $meses);
	// }

	// public function summarize(array $dados) {
	// 	return $this->defaultReponse(200, '', $this->repository->summarize($dados));
	// }
}
