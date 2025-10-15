<?php

namespace App\Domain\Jobs\Repositories;

use App\Domain\Jobs\Interfaces\RepositoryInterface;
use App\Domain\Jobs\Models\Ponto;
use Illuminate\Support\Collection;

class PontoRepository implements RepositoryInterface
{
	protected $model;

	public function __construct(Ponto $ponto)
	{
		$this->model = $ponto;
	}

	public function search(array $criteria): Collection
	{
		return $this->model->all();
	}

	public function find(int $id): ?Ponto
	{
		return $this->model->findOrFail($id);
	}

	public function create(array $data): Ponto
	{
		return $this->model->create($data);
	}

	public function update(int $id, array $data): bool
	{
		$ponto = $this->find($id);
		return $ponto->update($data);
	}

	public function delete(int $id): bool
	{
		$ponto = $this->find($id);
		return $ponto->delete();
	}
}
