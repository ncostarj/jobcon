<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

abstract class BaseService
{
	protected $response;

	public function defaultReponse($status, $message, $data) : JsonResponse {
		return response()->json([
			'status' => $status,
			'message' => $message,
			'data' => $data
		]);
	}
}
