<?php

namespace App\Domain\Jobs\Repositories;

use App\Domain\Jobs\Models\Frequencia;
use Illuminate\Support\Facades\Log;

class FrequenciaRepository
{
	private $model;

	public function __construct() {
		$this->model = Frequencia::class;
	}

	public function get(array $data = [])
	{
		return $this->model::query()
			->with('usuario')
			->where('user_id', $data['usuario_id'])
			->get();

	}
}
