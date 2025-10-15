<?php

namespace App\Domain\Jobs\Repositories;

use App\Domain\Jobs\Interfaces\RepositoryInterface;
use App\Domain\Jobs\Models\Contracheque;
use App\Domain\Jobs\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ContrachequeRepository implements RepositoryInterface
{
	protected $model;

	public function __construct(Contracheque $contracheque)
	{
		$this->model = $contracheque;
	}

	public function search(array $criteria): Collection
	{
		logger($criteria);
		return $this->model
			->where('user_id', $criteria['usuario_id'])
			->when($criteria['ano'] ?? false, function ($query, $ano) {
				return $query->whereRaw('YEAR(competencia) = ?', [$ano]);
			})
			->limit($criteria['limite'] ?? 5)
			->orderBy('competencia', $criteria['ordem'] ?? 'desc')
			->get();
	}

	public function find(string $id): ?Contracheque
	{
		return $this->model->findOrFail($id);
	}

	public function create(array $data): Contracheque
	{
		$this->model->fill($data);
		$this->model->usuario()->associate(User::where('id', $data['usuario_id'])->first());
		$this->model->empresa()->associate(Empresa::where('id', $data['empresa_id'])->first());
		$this->model->save();
		return $this->model;
	}

	public function update(string $id, array $data): bool
	{
		$contracheque = $this->find($id);
		return $contracheque->update($data);
	}

	public function delete(string $id): bool
	{
		$contracheque = $this->find($id);
		return $contracheque->delete();
	}

	public function getYears(array $criteria): Collection
	{
		return $this->model
			->query()
			->where('user_id', $criteria['usuario_id'])
			->selectRaw('date_format(competencia, "%Y") as ano')
			->groupBy(DB::raw('date_format(competencia, "%Y")'))
			->orderBy('ano', 'desc')
			->get();
	}
}
