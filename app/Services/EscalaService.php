<?php

namespace App\Services;

use App\Services\BaseService;
use App\Resources\EscalaResource;
use App\Repositories\EscalaRepository;

class EscalaService extends BaseService
{
	protected $repository;

	public function __construct()
	{
		$this->repository = new EscalaRepository;
	}

	public function search($data = [])
	{
		return $this->defaultReponse(200, 'Dados Retornados com sucesso',(new EscalaResource($this->repository->get($data)))->toArray());
	}
}
