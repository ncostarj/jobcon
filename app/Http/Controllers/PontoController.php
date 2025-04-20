<?php

namespace App\Http\Controllers;

use App\Http\Requests\PontoRequest;
use App\Models\Horario;
use App\Models\Ponto;
use App\Models\Saudacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\PontoRepository;
use App\Models\Slack\SlackNotification;
use App\Repositories\HorarioRepository;

class PontoController extends Controller
{
	//

	public function index(Request $request)
	{

		$icons = [
			'home_office' => 'bi bi-house',
			'presencial' => 'bi bi-building',
			'entrada' => 'bi bi-arrow-right-circle-fill',
			'almoco_saida' => 'bi bi-pause-circle-fill',
			'almoco_retorno' => 'bi bi-play-circle-fill',
			'saida' => 'bi bi-arrow-left-circle-fill'
		];

		$pontos = Ponto::with('horarios')->orderBy('dia','desc')->get();

		return view('jobs.pontos.index', compact('pontos', 'icons'));

	}

	public function create()
	{
		return view('jobs.pontos.form');
	}

	public function store(Request $request, PontoRepository $horarioRepository)
	{
		// $horarioRepository->insert($request->only(['dia', 'hora', 'tipo', 'pedir_ajuste', 'observacao']));
		return redirect()->route('jobs.pontos.form');
	}

	public function edit(Ponto $ponto)
	{
		return view('jobs.pontos.form', compact('ponto'));
	}

	public function update(Ponto $ponto, PontoRequest $request)
	{

		$pontoData = $request->only('dia','categoria','pedir_ajuste','observacao');
		$ponto->update($pontoData);

		if($request->has('entrada') && !empty($request->input('entrada'))) {
            $horario = Horario::query()
				->where([
					[ 'ponto_id', '=', $ponto->id ],
					[ 'tipo', '=', 'entrada' ]
				])
				->update([
					'hora' => $request->input('entrada')
				]);
		}

		if($request->has('almoco_saida') && !empty($request->input('almoco_saida'))) {
			$horario = Horario::query()
			->where([
				[ 'ponto_id', '=', $ponto->id ],
				[ 'tipo', '=', 'almoco_saida' ]
			])->first();

			if(empty($horario)) {
				$horario = new Horario;
				$horario->ponto()->associate($ponto);
				$horario->fill([
						'hora' => $request->input('almoco_saida'),
						'tipo' => 'almoco_saida'
					])->save();
			} else {
				$horario->update([
					'hora' => $request->input('almoco_saida')
				]);
			}
		}

		if($request->has('almoco_retorno') && !empty($request->input('almoco_retorno'))) {
			$horario = Horario::query()
			->where([
				[ 'ponto_id', '=', $ponto->id ],
				[ 'tipo', '=', 'almoco_retorno' ]
			])->first();

			if(empty($horario)) {
				$horario = new Horario;
				$horario->ponto()->associate($ponto);
				$horario->fill([
						'hora' => $request->input('almoco_retorno'),
						'tipo' => 'almoco_retorno'
					])->save();
			} else {
				$horario->update([
					'hora' => $request->input('almoco_retorno')
				]);
			}
		}

		if($request->has('saida') && !empty($request->input('saida'))) {
			$horario = Horario::query()
			->where([
				[ 'ponto_id', '=', $ponto->id ],
				[ 'tipo', '=', 'saida' ]
			])->first();

			if(empty($horario)) {
				$horario = new Horario;
				$horario->ponto()->associate($ponto);
				$horario->fill([
						'hora' => $request->input('saida'),
						'tipo' => 'saida'
					])->save();
			} else {
				$horario->update([
					'hora' => $request->input('saida')
				]);
			}
		}

		return redirect()->route('jobs.dashboard.index');
	}

	public function destroy(Horario $horario)
	{
		return redirect()->route('jobs.horarios.index');
	}

	public function assign(Request $request, PontoRepository $horarioRepository, SlackNotification $slackNotification)
	{
		switch ($request->tipo) {
			case 'entrada':
				$saudacao = new Saudacao("Bom dia!", ":house:");
				break;  //= 'Bom dia';
			case 'almoco_saida':
				$saudacao = new Saudacao("Almoço!", ":knife_fork_plate:");
				break; //'Almoço';
			case 'almoco_retorno':
				$saudacao = new Saudacao("Voltando!", ":house:");
				break; //'Voltando'
			case 'saida':
				$saudacao = new Saudacao("Saindo!", ":bed:");
				break; //'Saindo';
		}

		// Log::info((array) $saudacao);

		$dados = array_merge($request->only('tipo', 'categoria'), [
			'dia' => date('Y-m-d'),
			'hora' => date("H:i"),
			'pedir_ajuste' => 0,
			'observacao' => ''
		]);

		$horarioRepository
			->insert($dados);

		// // $slackNotification
		// // 	->setToken(config('slack.SLACK_BOT_USER_OAUTH_TOKEN'))
		// // 	->setChannel(config('slack.SLACK_BOT_USER_CHANNEL_ID'))
		// // 	->setStatus($saudacao->getIcone())
		// // 	->setMessage($saudacao->getTexto())
		// // 	->from("Heric de Honkis e Branco")
		// // 	->notify();

		return redirect()->back();
	}
}
