<?php

namespace App\Services;

use App\Models\Contracheque;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Resources\ContrachequeResource;
use App\Repositories\ContrachequeRepository;

class ContrachequeService extends BaseService
{

	protected $repository;

	public function __construct()
	{
		$this->repository = new ContrachequeRepository;
	}

	public function get(array $dados = [])
	{
		return $this->defaultReponse(200, 'Dados retornados com sucesso.', (new ContrachequeResource($this->repository->get($dados)))->toArray());
	}

	public function searchYears(array $dados) {
		$query = Contracheque::query()
			->selectRaw('date_format(competencia, "%Y") as ano');
		// if($dados['ano']) {
		// 	$query = $query
		// 		->whereRaw('date_format(competencia, "%Y") = ?', [ "'{$dados['ano']}'" ]);
		// }
		// // $anos =
		// // $anos
		// // $ano = $dados['ano']??null;
		// // ->when($ano, function($query,$ano) {
		// // 	return $query->whereRaw('date_format(competencia, "%Y")','=', [ $ano ]);
		// // })
		$anos = $query
		->groupBy(DB::raw('date_format(competencia, "%Y")'))
		->orderBy('ano', 'desc')
		->get();
		return $this->defaultReponse(200, 'Dados retornados com sucesso.', $anos);
	}
}
