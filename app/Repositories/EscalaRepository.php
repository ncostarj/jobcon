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
		// $mes = date('m');
		return $this->model::with('usuario')
			// ->whereRaw('MONTH(dia) = ?', [ $mes ])
			->orderBy('dia','asc')
			->get();
	}
}
