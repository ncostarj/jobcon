<?php

namespace App\Domain\Jobs\Repositories;

use App\Domain\Jobs\Interfaces\RepositoryInterface;
use App\Domain\Jobs\Models\Horario;
use App\Models\User;
use Illuminate\Support\Collection;

class HorarioRepository implements RepositoryInterface
{
	protected $model;

	public function __construct(Horario $ponto)
	{
		$this->model = $ponto;
	}

	public function search(array $criteria): Collection
	{
		return $this->model
			->where('user_id', $criteria['usuario_id'])
			->when($criteria['dia']??false, function($query, $dia) {
				return $query->where('dia', $dia);
			})
			->when($criteria['mes'] ?? false, function ($query, $mes) {
				return $query->whereRaw('MONTH(dia) = ?', [$mes]);
			})
			->when($criteria['ano'] ?? false, function ($query, $ano) {
				return $query->whereRaw('YEAR(dia) = ?', [$ano]);
			})
			->orderBy('dia', $criteria['ordem'] ?? 'desc')
			->get();
	}

	public function find(string $id): ?Horario
	{
		return $this->model->findOrFail($id);
	}

	public function create(array $data): Horario
	{

		dump('123');

		$mass = collect($data);
		dd($mass);
		$massPonto = $mass->only('dia', 'categoria', 'pedir_ajuste', 'ajuste_finalizado', 'observacao_dia')->toArray();
		$massPonto['observacao'] = $massPonto['observacao_dia'];
		unset($massPonto['observacao_dia']);

		$model = $this->model->fill($massPonto);
		$model->usuario()->associate(User::where('id', $mass->get('usuario_id'))->first());
		$model->save();

		if($mass->has('hora')) {

		}

		dd($model);

		// $this->model->create($data);

		dd(__LINE__);

		// $massPonto['observacao'] = $massPonto['observacao_dia'];
		// unset($massPonto['observacao_dia']);

		// $model = $this->model->fill($massPonto);
		// $model->usuario()->associate(User::where('id', $mass->get('usuario_id')));
		// $model->save();

		// logger([ $model->toJson() ]);

		// if($mass->has('horario')) {

		// 	$model->horario()->save();
		// }


		return $model;
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
}
