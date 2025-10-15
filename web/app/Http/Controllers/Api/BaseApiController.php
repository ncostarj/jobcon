<?php

namespace App\Http\Controllers\Api;

use App\Domain\Jobs\Interfaces\ControllerInterface;
use App\Http\Controllers\Controller;

class BaseApiController extends Controller {

	public function response(int $code, string $message, array $data = []) {
		return response()->json([ 'status_code' => $code, 'message' => $message, 'data' => $data ]);
	}

}
