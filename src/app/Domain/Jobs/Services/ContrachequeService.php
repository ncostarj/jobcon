<?php

namespace App\Domain\Jobs\Services;

use App\Domain\Jobs\Contracts\ServiceInterface;
use App\Domain\Jobs\Models\Contracheque;
use App\Domain\Jobs\Repositories\ContrachequeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ContrachequeService implements ServiceInterface
{

	protected $repository;

	public function __construct(ContrachequeRepository $repository)
	{
		$this->repository = $repository;
	}

	public function search(Request $request): Collection
	{
		return $this->repository->search($request->all());
	}

	public function find(string $id): ?Contracheque
	{
		return $this->repository->find($id);
	}

	public function create(Request $request): Contracheque
	{
		return $this->repository->create($request->all());
	}

	public function update(string $id, Request $request): bool
	{
		return $this->repository->update($id, $request->all());
	}

	public function delete(string $id): bool
	{
		return $this->repository->delete($id);
	}

	public function getYears(array $criteria) : Collection {
		return $this->repository->getYears($criteria);
	}
}
