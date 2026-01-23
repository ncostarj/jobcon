<?php

namespace App\Domain\Jobs\Repositories;

use App\Domain\Jobs\Contracts\RepositoryInterface;
use App\Domain\Jobs\Models\Horario;
use App\Domain\Jobs\Models\Ponto;
use App\Models\User;
use Illuminate\Support\Collection;

class HorarioRepository implements RepositoryInterface
{
	protected $model;

	public function __construct(Horario $horario)
	{
		$this->model = $horario;
	}

	public function search(array $criteria): Collection
	{
		return $this->model
			->when($criteria['ponto_id'] ?? false, fn($query, $ponto_id) => $query->where('ponto_id', $ponto_id))
			->when($criteria['tipo'] ?? false, fn($query, $tipo) => $query->where('tipo', $tipo))
			->when($criteria['hora'] ?? false, fn($query, $hora) => $query->where('hora', $hora))
			->orderBy('hora', 'asc')
			->get();
	}

	public function find(string $id): ?Horario
	{
		return $this->model->findOrFail($id);
	}

	public function create(array $data): Horario
	{
		return $this->model->create($data);
	}


	public function createWithPonto(array $data): Horario
	{
		$model = new Horario;
		$model->ponto()->associate($data['ponto']);
		$model->fill($data)->save();
		return $model;
	}

	public function update(string $id, array $data): bool
	{
		$horario = $this->find($id);
		return $horario->update($data);
	}

	public function delete(string $id): bool
	{
		$horario = $this->find($id);
		return $horario->delete();
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
}
