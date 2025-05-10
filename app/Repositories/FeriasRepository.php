<?php

namespace App\Repositories;

use App\Models\Ferias;
use Illuminate\Support\Facades\Log;

class FeriasRepository
{
	private $model;

	public function __construct() {
		$this->model = Ferias::class;
	}

	public function get(array $data = [])
	{
		extract($data??[]);

		$rs = $this->model::query()
			->with('usuario')
			->where([
				[ 'user_id', '=', $usuario_id ]
			])
			->orderBy('inicio', 'desc');

		if(isset($data['limite'])) {
			$rs->limit($data['limite']);
		}

		return 	$rs->get();
	}

	public function insert(array $data)
	{
		return $this->model::create($data);
	}

	public function update(string $id, array $data) {
		return $this->model::where('id', $id)
			->fill($data)
			->update($data);
	}

	public function delete($id) {
		return $this->model::where('id', $id)
			->delete();
	}

	public function getUltimaFeriasAgenda($data) {
		return $this->model::query()
			->when($data['usuario_id'], function($query) use($data) {
				return $query->where('user_id', $data['usuario_id']);
			})
			->orderBy('inicio', 'desc')->take(1)->first();
	}
}
