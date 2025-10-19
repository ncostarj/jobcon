<?php

namespace App\Domain\Jobs\Repositories;

use App\Domain\Jobs\Interfaces\RepositoryInterface;
use App\Domain\Jobs\Models\Ponto;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PontoRepository implements RepositoryInterface
{
	protected $model;
	protected $horarioRepository;

	public function __construct(Ponto $ponto, HorarioRepository $horarioRepository)
	{
		$this->model = $ponto;
		$this->horarioRepository = $horarioRepository;
	}

	public function search(array $criteria): Collection
	{
		return $this->model
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
		$model = $this->createPonto($data);
		$this->createHorario($model, $data['horario']);
		return $model;
	}

	private function createPonto(array $data) : Ponto{
		$model = $this->model->fill($data);
		$model->usuario()->associate(User::where('id', $data['user_id'])->first());
		$model->save();
		return $model;
	}

	private function createHorario(Ponto $model, array $horarioData) {
		$horario = $this->horarioRepository->create($horarioData);
		$model->horarios()->save($horario);
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

	public function assign() {}

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
