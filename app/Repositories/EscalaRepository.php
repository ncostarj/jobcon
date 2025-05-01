<?php

namespace App\Repositories;

use App\Models\Escala;
use Illuminate\Support\Facades\Log;

class EscalaRepository
{
	private $model;

	public function __construct() {
		$this->model = Escala::class;
	}

	public function get(array $data = []) {
		return $this->model::query()
			->with('integrantes')
			->get();
	}
}
