<?php

namespace App\Models\Common;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseGateway
{
	const RESPONSE_DATA = 1;
	const RESPONSE_JSON = 2;
	const RESPONSE_ARRAY = 3;
	const RESPONSE_COLLECTION = 4;

	protected $auth;
	protected $headers;
	protected $httpClient;
	protected $baseUrl;
	protected $endpoint;
	protected $response;

	public function __construct($baseUrl, $auth) {
		$this->baseUrl = $baseUrl;
		$this->auth =  $auth;
		$this->headers = [
			'Content-Type' => 'application/json'
		];
	}

	public function setBaseUrl(string $baseUrl) {
		$this->baseUrl = $baseUrl;
		return $this;
	}

	public function setHeaders(array $headers) {
		$this->headers = $headers;
		return $this;
		// $this->httpClient = $this->httpClient::withHeaders($headers);
	}

	protected function sendRequest(string $endpoint, string $method = 'get', array $data = [], int $returnType = self::RESPONSE_DATA, bool $debug = false)
	{
		$url = $this->baseUrl . $endpoint;

		$httpClient = Http::withBasicAuth($this->auth['username'], $this->auth['password'])
			->withHeaders($this->headers);

		switch($method) {
			case 'get':
			case 'GET':
				$this->response = $httpClient->get($url, $data);
				break;
			case 'post':
			case 'POST':
				$this->response = $httpClient->post($url, $data);
				break;
		}

		$statusCode = $this->response->getStatusCode();
		return $this->defaultReponse($statusCode, trans("api.{$statusCode}"), $this->response, $returnType);
	}

	protected function toObject() {
		return json_decode($this->response);
	}

	protected function toJson() {
		return $this->response->json();
	}

	protected function toArray() {
		return json_decode($this->response, true);
	}

	protected function toCollection() {
		return collect($this->toArray());
	}

	protected function toText() {
		return $this->response->body();
	}


	public function defaultReponse($status, $message, $response, $returnType)  {

		switch ($returnType) {
			case self::RESPONSE_DATA:
				$data = json_decode($response);
				break;
			case self::RESPONSE_JSON:
				$data = $response->json();
				break;
			case self::RESPONSE_ARRAY:
				$data = json_decode($response, true);
				break;
			case self::RESPONSE_COLLECTION:
				$data = collect(json_decode($response));
				break;
			default:
				$data = $response->body();
		}

		// [
		// 	'status' => $status,: JsonResponse
		// 	'message' => $message,
		// 	'data' => $data
		// ]
		$response = compact('status', 'message', 'data');
		// Log::info($response);
		// return response()->json($response);
		return $response;
	}
}
