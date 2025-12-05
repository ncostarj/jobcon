<?php

namespace App\Http\Controllers\Api;

use App\Domain\Jobs\Resources\FrequenciaResource;
use App\Domain\Jobs\Services\FrequenciaService;
use Illuminate\Http\Request;

class FrequenciaController extends BaseApiController
{
	public function indexUltimoSaldo(FrequenciaService $frequenciaService, Request $request) {
		try {
			$response = $this->response(200, trans('api.200'), [ 'frequencia' => $frequenciaService->getLastSaldo($request->all()) ]);
		} catch (\Throwable $th) {
			$this->log($th);
			$response = $this->response(500, trans('api.500'));
		}
		return $response;
	}
}
