<?php

namespace App\Http\Controllers\Api;

use App\Domain\Jobs\Contracts\ControllerInterface;
use App\Http\Controllers\Controller;
use Throwable;

class BaseApiController extends Controller
{

	public function response(int $code, string $message, $data = null)
	{
		return response([ 'code' => $code, 'message' => $message, 'data' => $data ], $code);
	}

	protected function log(Throwable $th)
	{
		$error  = <<<TEXT
		{$th->getFile()}:{$th->getLine()}
		{$th->getMessage()}
		TEXT;
		logger($error);
	}
}
