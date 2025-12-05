<?php

namespace App\Domain\Jobs\Repositories;

use App\Domain\Jobs\Contracts\RepositoryInterface;
use App\Domain\Jobs\DTOs\FeriasDTO;
use App\Domain\Jobs\Models\Ferias;
use Illuminate\Support\Collection;

class FeriasRepository implements RepositoryInterface
{
	private $model;

	public function __construct(Ferias $ferias)
	{
		$this->model = $ferias;
	}

	public function search(array $data = []): Collection
	{
		return $this->model
			->where('user_id', $data['usuario_id'])
			->orderBy('inicio', 'desc')
			->limit($data['limite'] ?? 5)
			->get();
	}

	public function find(string $id): ?Ferias
	{
		return $this->model->findOrFail($id);
	}

	public function create(array $data): Ferias
	{
		return $this->model::create($data);
	}

	public function createWithUser(FeriasDTO $dto) {
		$ferias = $dto->toArray();
		$this->model->usuario()->associate($ferias['usuario']);
		$this->model->fill($ferias)->save();
		return $this->model;
	}

	public function update(string $id, array $data): bool
	{
		$model = $this->model->find($id);
		$model->usuario()->associate($data['usuario']);
		return $model->fill($data)->update();
	}

	public function delete($id): bool
	{
		$model = $this->find($id);
		return $model->delete();
	}

	public function getUltimaFeriasAgenda($data)
	{
		return $this->model::query()
			->when($data['usuario_id'], function ($query) use ($data) {
				return $query->where('user_id', $data['usuario_id']);
			})
			->orderBy('inicio', 'desc')->take(1)->first();
	}
}
