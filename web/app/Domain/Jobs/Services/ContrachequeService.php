<?php

namespace App\Domain\Jobs\Services;

use App\Domain\Jobs\Interfaces\ServiceInterface;
use App\Domain\Jobs\Models\Contracheque;
use Illuminate\Support\Collection;

class ContrachequeService implements ServiceInterface
{

	protected $repository;

	public function __construct(ServiceInterface $repository)
	{
		$this->repository = $repository;
	}

	public function search(array $criteria): Collection
	{
		return $this->repository->search($criteria);
	}

	public function find(int $id): ?Contracheque
	{
		return $this->repository->find($id);
	}

	public function create(array $data): Contracheque
	{
		return $this->repository->create($data);
	}

	public function update(int $id, array $data): bool
	{
		return $this->repository->update($id, $data);
	}

	public function delete(int $id): bool
	{
		return $this->repository->delete($id);
	}	

	// public function get(array $dados = [])
	// {
	// 	return $this->defaultReponse(200, 'Dados retornados com sucesso.', (new ContrachequeResource($this->repository->get($dados)))->toArray());
	// }

	// public function searchYears(array $dados) {
	// 	$query = Contracheque::query()
	// 		->when($dados['usuario_id'], function($query) use ($dados){
	// 			return $query->where('user_id', $dados['usuario_id']);
	// 		})
	// 		->selectRaw('date_format(competencia, "%Y") as ano');
	// 	// if($dados['ano']) {
	// 	// 	$query = $query
	// 	// 		->whereRaw('date_format(competencia, "%Y") = ?', [ "'{$dados['ano']}'" ]);
	// 	// }
	// 	// // $anos =
	// 	// // $anos
	// 	// // $ano = $dados['ano']??null;
	// 	// // ->when($ano, function($query,$ano) {
	// 	// // 	return $query->whereRaw('date_format(competencia, "%Y")','=', [ $ano ]);
	// 	// // })
	// 	$anos = $query
	// 	->groupBy(DB::raw('date_format(competencia, "%Y")'))
	// 	->orderBy('ano', 'desc')
	// 	->get();
	// 	return $this->defaultReponse(200, 'Dados retornados com sucesso.', $anos);
	// }
}
