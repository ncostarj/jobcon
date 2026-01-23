<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Domain\Jobs\Resources\PontoMesResource;
use App\Domain\Jobs\Resources\PontoResource;
use App\Domain\Jobs\Resources\PontoResumoResource;
use App\Domain\Jobs\Services\PontoService;

class PontoController extends BaseApiController
{
	public function index(PontoService $pontoService, Request $request)
	{
		try {
			$response = $this->response(200, trans('api.200'), PontoResource::toArray($pontoService->search($request)));
		} catch (\Throwable $th) {
			$this->log($th);
			$response = $this->response(500, trans('api.500'));
		}

		return $response;
	}

	public function assign(PontoService $pontoService, Request $request)
	{
		try {
			$response = $this->response(200, trans('api.200'), ['ponto' => $pontoService->assign($request)]);
		} catch (\Throwable $th) {
			$this->log($th);
			$response = $this->response(500, trans('api.500'));
		}

		return $response;
	}

	public function summarize(PontoService $pontoService, Request $request)
	{
		try {
			$response = $this->response(200, trans('api.200'), PontoResumoResource::toArray($pontoService->summarize($request->all())) );
		} catch (\Throwable $th) {
			$this->log($th);
			$response = $this->response(500, trans('api.500'));
		}

		return $response;
	}

	public function indexMeses(PontoService $pontoService, Request $request)
	{
		try {
			$response = $this->response(200, trans('api.200'), PontoMesResource::toArray($pontoService->getMonths($request->all())));
		} catch (\Throwable $th) {
			$this->log($th);
			$response = $this->response(500, trans('api.500'));
		}

		return $response;
	}

	public function calculateSubtotalHoras(PontoService $pontoService, Request $request)
	{
		try {
			$response = $this->response(200, trans('api.200'), $pontoService->calculateSubtotalHoras($request->all()));
		} catch (\Throwable $th) {
			$this->log($th);
			$response = $this->response(500, trans('api.500'));
		}

		return $response;
	}

	public function update(string $id, Request $request)
	{

		$validated = $request->validate();
		dd($validated);

		// $pontoData = $request->only('dia','categoria','pedir_ajuste','ajuste_finalizado','observacao');
		// $ponto->update($pontoData);

		// if($request->has('entrada') && !empty($request->input('entrada'))) {
		//     $horario = Horario::query()
		// 		->where([
		// 			[ 'ponto_id', '=', $ponto->id ],
		// 			[ 'tipo', '=', 'entrada' ]
		// 		])
		// 		->update([
		// 			'hora' => $request->input('entrada')
		// 		]);
		// }

		// if($request->has('almoco_saida') && !empty($request->input('almoco_saida'))) {
		// 	$horario = Horario::query()
		// 	->where([
		// 		[ 'ponto_id', '=', $ponto->id ],
		// 		[ 'tipo', '=', 'almoco_saida' ]
		// 	])->first();

		// 	if(empty($horario)) {
		// 		$horario = new Horario;
		// 		$horario->ponto()->associate($ponto);
		// 		$horario->fill([
		// 				'hora' => $request->input('almoco_saida'),
		// 				'tipo' => 'almoco_saida'
		// 			])->save();
		// 	} else {
		// 		$horario->update([
		// 			'hora' => $request->input('almoco_saida')
		// 		]);
		// 	}
		// }

		// if($request->has('almoco_retorno') && !empty($request->input('almoco_retorno'))) {
		// 	$horario = Horario::query()
		// 	->where([
		// 		[ 'ponto_id', '=', $ponto->id ],
		// 		[ 'tipo', '=', 'almoco_retorno' ]
		// 	])->first();

		// 	if(empty($horario)) {
		// 		$horario = new Horario;
		// 		$horario->ponto()->associate($ponto);
		// 		$horario->fill([
		// 				'hora' => $request->input('almoco_retorno'),
		// 				'tipo' => 'almoco_retorno'
		// 			])->save();
		// 	} else {
		// 		$horario->update([
		// 			'hora' => $request->input('almoco_retorno')
		// 		]);
		// 	}
		// }

		// if($request->has('saida') && !empty($request->input('saida'))) {
		// 	$horario = Horario::query()
		// 	->where([
		// 		[ 'ponto_id', '=', $ponto->id ],
		// 		[ 'tipo', '=', 'saida' ]
		// 	])->first();

		// 	if(empty($horario)) {
		// 		$horario = new Horario;
		// 		$horario->ponto()->associate($ponto);
		// 		$horario->fill([
		// 				'hora' => $request->input('saida'),
		// 				'tipo' => 'saida'
		// 			])->save();
		// 	} else {
		// 		$horario->update([
		// 			'hora' => $request->input('saida')
		// 		]);
		// 	}
		// }

		// return redirect()->route('jobs.dashboard.index');
	}

	// public function destroy(Horario $horario)
	// {
	// 	return redirect()->route('jobs.horarios.index');
	// }

	// public function assign(Request $request, PontoRepository $horarioRepository, SlackNotification $slackNotification)
	// {
	// 	// switch ($request->tipo) {
	// 	// 	case 'entrada':
	// 	// 		$saudacao = new Saudacao("Bom dia!", ":house:");
	// 	// 		break;  //= 'Bom dia';
	// 	// 	case 'almoco_saida':
	// 	// 		$saudacao = new Saudacao("Almoço!", ":knife_fork_plate:");
	// 	// 		break; //'Almoço';
	// 	// 	case 'almoco_retorno':
	// 	// 		$saudacao = new Saudacao("Voltando!", ":house:");
	// 	// 		break; //'Voltando'
	// 	// 	case 'saida':
	// 	// 		$saudacao = new Saudacao("Saindo!", ":bed:");
	// 	// 		break; //'Saindo';
	// 	// }

	// 	// Log::info((array) $saudacao);

	// 	$dados = array_merge($request->only('tipo', 'categoria'), [
	// 		'dia' => date('Y-m-d'),
	// 		'hora' => date("H:i"),
	// 		'pedir_ajuste' => 0,
	// 		'observacao' => ''
	// 	]);

	// 	$horarioRepository
	// 		->insert($dados);

	// 	// // $slackNotification
	// 	// // 	->setToken(config('slack.SLACK_BOT_USER_OAUTH_TOKEN'))
	// 	// // 	->setChannel(config('slack.SLACK_BOT_USER_CHANNEL_ID'))
	// 	// // 	->setStatus($saudacao->getIcone())
	// 	// // 	->setMessage($saudacao->getTexto())
	// 	// // 	->from("Heric de Honkis e Branco")
	// 	// // 	->notify();

	// 	return redirect()->back();
	// }
}
