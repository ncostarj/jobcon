<?php

namespace App\Repositories;

use App\Models\Contracheque;
use Illuminate\Support\Facades\Log;

class ContrachequeRepository
{
	private $model;

	public function __construct() {
		$this->model = Contracheque::class;
	}

	public function get(array $data = [])
	{
		extract($data??[]);

		return $this->model::query()
			->with('usuario')
			->where([
				[ 'user_id', '=', $usuario_id ]
			])
			->when($ano, function($query, $ano) {
				return $query->whereRaw("date_format(competencia,'%Y') = ?", [ $ano ]);
			})
			->orderBy('competencia', 'desc')
			->get();
	}
}
