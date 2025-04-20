<?php

namespace App\Services;

use Carbon\Carbon;
use App\Services\BaseService;
use App\Resources\FeriasResource;
use Illuminate\Support\Facades\Log;
use App\Repositories\FeriasRepository;

class FeriasService extends BaseService
{

	protected $repository;

	public function __construct()
	{
		$this->repository = new FeriasRepository;
	}

	public function get(array $dados = [])
	{
		return $this->defaultReponse(200, 'Dados retornados com sucesso.', (new FeriasResource($this->repository->get($dados)))->toArray());
	}

	public function insert($data)
	{
		return $this->defaultReponse(200, 'Dados retornados com sucesso.', $this->repository->insert($data));
	}

	public function verifyDiasAteFerias(array $data)
	{
		$retorno = null;

		$hoje = Carbon::parse(date('Y-m-d'));
		$ultimaFeriasAgendada = $this->repository->getUltimaFeriasAgenda();
		$inicio = !empty($ultimaFeriasAgendada) ? $ultimaFeriasAgendada->inicio->format('Y-m-d') : '';

		if(!empty($ultimaFeriasAgendada) && ($ultimaFeriasAgendada->ativo || $inicio > $hoje->format('Y-m-d'))) {
			$dataInicio = Carbon::parse($ultimaFeriasAgendada->inicio);
			$dataFim = Carbon::parse($ultimaFeriasAgendada->fim);
			$diferencaEmDiasInicio = $dataInicio->diffInDays($hoje);
			$diferencaEmDiasRetorno = $hoje->diffInDays($dataFim);
			$retorno = [
				'diasAteFerias' => $diferencaEmDiasInicio,
				'diasAteRetorno' => $diferencaEmDiasRetorno,
				'ativo' => $ultimaFeriasAgendada->ativo==1
			];
		}

		return $this->defaultReponse(200, 'Dados retornados com sucesso.', $retorno);
	}
}
