<?php

namespace App\Repositories;

use App\Models\Frequencia;
use Illuminate\Support\Facades\Log;

class FrequenciaRepository
{
	private $model;

	public function __construct() {
		$this->model = Frequencia::class;
	}

	public function get(array $data = [])
	{
		extract($data??[]);

		return $this->model::query()
			->with('usuario')
			->when($data['usuario_id'], function($query) use ($data){
				return $query->where('user_id', $data['usuario_id']);
			})
			->get();

	}
}
