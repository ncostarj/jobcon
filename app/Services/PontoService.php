<?php

namespace App\Services;

use App\Models\Common\MyCalendar;
use App\Models\Slack\SlackNotification;
use App\Resources\PontoResource;
use Illuminate\Support\Facades\Log;
use App\Repositories\PontoRepository;
use Illuminate\Support\Facades\Http;

class PontoService extends BaseService
{

	protected $repository;

	public function __construct()
	{
		$this->repository = new PontoRepository;
	}

	public function get(array $dados = [])
	{
		return $this->defaultReponse(200, '', (new PontoResource($this->repository->get($dados)))->toArray());
	}

	public function assign($dados)
	{
		$response = $this->repository->insert($dados);
		$this->notifyAssign($dados);
		return $this->defaultReponse(200, '', $response);
		// $this->sendBotAssign($dados);
		// return $this->defaultReponse(200, '', []);
	}

	private function sendBotAssign($dados) {
		logger($dados);
		$response = Http::get('http://jobconrpa:3000/efetuar-marcacao', $dados);
		logger($response);
	}

	private function notifyAssign($dados)
	{
		// Log::info($dados);
		$categoria = $dados['categoria'] == 'home_office' ? ':house:' : ':ot:';
		switch($dados['tipo']) {
			case 'entrada': $texto = 'Bom dia'; $icone = $categoria;break;
			case 'almoco_saida': $texto = 'Almoço'; $icone = ':knife_fork_plate:';break;
			case 'almoco_retorno': $texto = 'Voltei'; $icone = $categoria;break;
			case 'saida':  $texto = 'Saindo';$icone = ':bed:';break;
			default: $texto = '-'; $icone = ':bomba:'; break;
		}

		Log::info("{$texto} {$icone}");

		(new SlackNotification())
			->setToken(config('slack.SLACK_BOT_USER_OAUTH_TOKEN'))
			->setChannel(config('slack.SLACK_BOT_USER_CHANNEL_ID'))
			->setMessage($texto)
			->setStatus($icone)
			->notify();
	}

	public function sumHours(array $dados)
	{
		$credito = strtotime("00:00");
		$debito = strtotime("00:00");
		// ['mes' => date('m'), 'usuario_id' => User::where('name', 'Newton Gonzaga Costa')->first()->id]
		foreach ($this->repository->get($dados) as $ponto) {

			if($ponto->debito != '00:00') {
				$addDebito = '+';

				[$hora,$minutos] = explode(':',$ponto->debito);

				if($hora != '00') {
					$addDebito .= " {$hora} hours";
				}

				if($minutos!= '00') {
					$addDebito .= " {$minutos} minutes";
				}

				$debito = strtotime($addDebito, $debito);
			}

			if($ponto->credito != '00:00') {
				$addCredito = '+';

				[$hora,$minutos] = explode(':',$ponto->credito);

				if($hora != '00') {
					$addCredito .= " {$hora} hours";
				}

				if($minutos!= '00') {
					$addCredito .= " {$minutos} minutes";
				}

				$credito = strtotime($addCredito, $credito);
			}

		}

		$debito = date('H:i', $debito);
		$credito = date('H:i', $credito);

		// Log::info("{$debito}|{$credito}");

		return $this->defaultReponse(200, '', (object) compact('debito', 'credito'));
	}

	public function searchMonths(array $dados) {
		$myCalendar = new MyCalendar();
		$listMes = $this->repository->searchMonths();

		$meses = [];
		foreach($listMes as $mes) {
			$meses[] = [
				'numero' => $mes->mes,
				'nome' => $myCalendar->getMes($mes->mes),
				'ano' => $mes->ano
			];
		}

		return $this->defaultReponse(200, '', $meses);
	}

	public function summarize(array $dados) {
		return $this->defaultReponse(200, '', $this->repository->summarize($dados));
	}
}
