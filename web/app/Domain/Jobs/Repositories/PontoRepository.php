<?php

namespace App\Domain\Jobs\Repositories;

use App\Domain\Jobs\Contracts\RepositoryInterface;
use App\Domain\Jobs\Models\Ponto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PontoRepository implements RepositoryInterface
{
	protected $model;

	public function __construct(Ponto $ponto)
	{
		$this->model = $ponto;
	}

	public function search(array $criteria): Collection
	{
		return $this->model
			->with(['usuario', 'horarios'])
			->where('user_id', $criteria['usuario_id'])
			->when($criteria['dia'] ?? false, fn($query, $dia) => $query->where('dia', $dia))
			->when($criteria['mes'] ?? false, fn($query, $mes) => $query->whereRaw('MONTH(dia) = ?', [$mes]))
			->when($criteria['ano'] ?? false, fn($query, $ano) => $query->whereRaw('YEAR(dia) = ?', [$ano]))
			->orderBy('dia', $criteria['ordem'] ?? 'desc')
			->get();
	}

	public function find(string $id): ?Ponto
	{
		return $this->model->findOrFail($id);
	}

	public function create(array $data): Ponto
	{
		return $this->model->create($data);
	}

	public function createWithUser(array $data): Ponto
	{
		$this->model->usuario()->associate($data['usuario']);
		$this->model->fill($data)->save();
		return $this->model;
	}

	public function update(string $id, array $data): bool
	{
		$ponto = $this->find($id);
		return $ponto->update($data);
	}

	public function delete(string $id): bool
	{
		$ponto = $this->find($id);
		return $ponto->delete();
	}

	public function summarize(array $criteria)
	{
		return $this->model
			->where('user_id', $criteria['usuario_id'])
			->when($criteria['mes'] ?? false, function ($query, $mes) {
				return $query->whereRaw('MONTH(dia) = ?', [$mes]);
			})
			->when($criteria['ano'] ?? false, function ($query, $ano) {
				return $query->whereRaw('YEAR(dia) = ?', [$ano]);
			})
			->get();
	}

	public function getMonths(array $criteria)
	{
		return $this->model
			->query()
			->selectRaw('date_format(dia, "%m") as mes, date_format(dia, "%Y") as ano')
			->when($criteria['usuario_id'] ?? false, function ($query, $usuario_id) {
				return $query->where('user_id', $usuario_id);
			})
			->groupBy(DB::raw('date_format(dia, "%m"), date_format(dia, "%Y")'))
			->orderBy('ano', 'desc')
			->orderBy('mes', 'desc')
			->get();
	}
}
