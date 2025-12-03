<?php

namespace App\Domain\Jobs\Services;

use Carbon\Carbon;
use App\Domain\Jobs\Repositories\FrequenciaRepository;
use App\Domain\Jobs\Resources\FrequenciaResource;

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
		return $this->repository->get($dados)
			->sortByDesc([
				fn ($a, $b) => $b['data'] <=> $a['data']
			])
			->first();
	}
}
