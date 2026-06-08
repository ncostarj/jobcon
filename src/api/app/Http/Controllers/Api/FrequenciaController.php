<?php

namespace App\Http\Controllers\Api;

use App\Domain\Jobs\Resources\FrequenciaResource;
use App\Domain\Jobs\Services\FrequenciaService;
use Illuminate\Http\Request;

class FrequenciaController extends BaseApiController
{
	public function indexUltimoSaldo(FrequenciaService $frequenciaService, Request $request) {
		try {
			$ultimoSaldo = $frequenciaService->getLastSaldo($request->all());
			$response = $this->response(200, trans('api.200'), [ 'frequencia' => !empty($ultimoSaldo) ? $ultimoSaldo : [ 'credito' => 0, 'debito' => 0 ] ]);
		} catch (\Throwable $th) {
			$this->log($th);
			$response = $this->response(500, trans('api.500'));
		}
		return $response;
	}
}
