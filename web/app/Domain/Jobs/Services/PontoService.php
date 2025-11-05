<?php

namespace App\Domain\Jobs\Services;

use App\Domain\Jobs\DTOs\HorarioDTO;
use App\Domain\Jobs\DTOs\PontoDTO;
use App\Domain\Jobs\Contracts\ServiceInterface;
use App\Domain\Jobs\Models\Ponto;
use App\Domain\Jobs\Repositories\HorarioRepository;
use App\Domain\Jobs\Repositories\PontoRepository;
use App\Domain\Jobs\Repositories\UsuarioRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PontoService implements ServiceInterface
{
	protected $pontoRepository;
	protected $horarioRepository;
	protected $usuarioRepository;

	public function __construct(PontoRepository $pontoRepository, HorarioRepository $horarioRepository, UsuarioRepository $usuarioRepository)
	{
		$this->pontoRepository = $pontoRepository;
		$this->horarioRepository = $horarioRepository;
		$this->usuarioRepository = $usuarioRepository;
	}

	public function search(Request $request): Collection
	{
		return $this->pontoRepository->search($request->all());
	}

	public function find(string $id): ?Ponto
	{
		return $this->pontoRepository->find($id);
	}

	public function create(Request $request): Ponto
	{
		return $this->pontoRepository->create($request->all());
	}

	public function update(string $id, Request $request): bool
	{
		$ponto = $this->pontoRepository->find($id);
		$pontoDTO = PontoDTO::fromRequest($request->merge(['usuario' => auth()->user()]));
		$horarios = collect($request->input('horarios'));
		$horarios->filter(fn($horario) => !empty($horario['hora']))
			->each(function ($horario) use ($ponto) {
				$horarioDTO = HorarioDTO::fromArray(array_merge($horario, ['ponto' => $ponto]));
				$searchData = ['ponto_id' => $ponto->id, 'tipo' => $horario['tipo']];
				$horarioModel = $this->horarioRepository->search($searchData)->first();

				if (!$horarioModel) {
					$this->horarioRepository->createWithPonto($horarioDTO->toArray());
				}

				if ($horarioModel) {
					$this->horarioRepository->update($horarioModel->id, $horarioDTO->toArray());
				}
			});
		return $this->pontoRepository->update($id, $pontoDTO->toArray());
	}

	public function delete(string $id): bool
	{
		return $this->pontoRepository->delete($id);
	}

	public function assign(Request $request): Ponto
	{
		$ponto = $this->pontoRepository
			->search([
				'usuario_id' => $request->input('usuario_id'),
				'dia' => $request->input('dia')
			])
			->first();

		$usuario = $this->usuarioRepository->search(['id' => $request->input('usuario_id')])->first();
		$pontoDTO = PontoDTO::fromRequest($request->merge(compact('usuario')));


		if (!$ponto) {
			$ponto = $this->pontoRepository->createWithUser($pontoDTO->toArray());
		}

		if ($ponto) {
			$this->pontoRepository->update($ponto->id, $pontoDTO->toArray());
		}

		$horarios = collect($request->input('horarios'));
		$horarios->each(function ($horario) use ($ponto) {
			$horarioDTO = HorarioDTO::fromArray(array_merge($horario, ['ponto' => $ponto]));
			$horarioModel = $this->horarioRepository->search(['ponto_id' => $ponto->id, 'tipo' => $horario['tipo']])->first();
			if (!$horarioModel) {
				$this->horarioRepository->createWithPonto($horarioDTO->toArray());
			}
		});

		return $ponto;
	}

	public function summarize(array $criteria)
	{
		return $this->pontoRepository->summarize($criteria);
	}

	public function getMonths(array $criteria): Collection
	{
		return $this->pontoRepository->getMonths($criteria);
	}


	public function calculateSubtotalHoras(array $criteria)
	{
		$credito = strtotime("00:00");
		$debito = strtotime("00:00");

		// ['mes' => date('m'), 'usuario_id' => User::where('name', 'Newton Gonzaga Costa')->first()->id]
		foreach ($this->pontoRepository->search($criteria) as $ponto) {

			$entrada = $ponto->entrada ? $ponto->entrada->hora->format('H:i'):'00:00';
			$almoco_saida = $ponto->almoco_saida ? $ponto->almoco_saida->hora->format('H:i'):'00:00';
			$almoco_retorno = $ponto->almoco_retorno ? $ponto->almoco_retorno->hora->format('H:i'):'00:00';
			$saida = $ponto->saida ? $ponto->saida->hora->format('H:i'):'00:00';

			preg_match('/(?<tipo>[+-])\s(?<hora>[0-9]{2}:[0-9]{2})/',$ponto->jornada_total, $match);

			$tipo = $match['tipo']??'';
			$hora = $match['hora']??'00:00';

			if($tipo == '' && $hora == '00:00') {
				continue;
			}

			match(true) {
				$match['tipo'] == '+' => $credito += strtotime($match['hora']),
				$match['tipo'] == '-' => $debito += strtotime($match['hora'])
			};
		}

		return [
			'credito' => date('H:i', $credito),
			'debito' => date('H:i', $debito)
		];

	}

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
