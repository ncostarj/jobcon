<?php

namespace App\Services;

use Carbon\Carbon;
use App\Services\BaseService;
use App\Repositories\FrequenciaRepository;
use App\Resources\FrequenciaResource;

class FrequenciaService extends BaseService
{

	protected $repository;

	public function __construct()
	{
		$this->repository = new FrequenciaRepository;
	}

	public function get(array $dados = [])
	{
		return $this->defaultReponse(200, 'Dados retornados com sucesso.', (new FrequenciaResource($this->repository->get($dados)))->toArray());
	}

	public function insert($data)
	{
		return $this->defaultReponse(200, 'Dados retornados com sucesso.', $this->repository->insert($data));
	}

	public function getLastSaldo(array $dados = []) {
		$ultimoBH = $this->repository->get($dados)
			->sortByDesc([
				fn ($a, $b) => $b['data'] <=> $a['data']
			])
			->first();

		return $this->defaultReponse(200, 'Dados retornados com sucesso.', (new FrequenciaResource([]))->toObject($ultimoBH));
	}
}
