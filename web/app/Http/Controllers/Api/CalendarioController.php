<?php

namespace App\Http\Controllers\Api;

use App\Domain\Jobs\Services\CalendarioService;
use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;

class CalendarioController extends BaseApiController
{
	//
	public function index(CalendarioService $calendarioService, Request $request) {
		try {
			$response = $this->response(200, trans('api.200'), $calendarioService->index());
		} catch (\Throwable $th) {
			$this->log($th);
			$response = $this->response(500, trans('api.500'));
		}

		return $response;
	}
}
