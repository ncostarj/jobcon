<?php

namespace App\Domain\Jobs\Repositories;

use App\Domain\Jobs\Interfaces\RepositoryInterface;
use App\Domain\Jobs\Models\Contracheque;
use Illuminate\Support\Collection;

class ContrachequeRepository implements RepositoryInterface
{
	protected $model;

	public function __construct(Contracheque $contracheque)
	{
		$this->model = $contracheque;
	}

	public function search(array $criteria): Collection
	{
		return $this->model->all();
	}

	public function find(int $id): ?Contracheque
	{
		return $this->model->findOrFail($id);
	}

	public function create(array $data): Contracheque
	{
		return $this->model->create($data);
	}

	public function update(int $id, array $data): bool
	{
		$contracheque = $this->find($id);
		return $contracheque->update($data);
	}

	public function delete(int $id): bool
	{
		$contracheque = $this->find($id);
		return $contracheque->delete();
	}
	
}
