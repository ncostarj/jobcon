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
			->where([
				[ 'user_id', '=', $usuario_id ]
			])
			->get();

	}
}
