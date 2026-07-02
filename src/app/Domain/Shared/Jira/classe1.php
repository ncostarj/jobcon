<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;

class Classe1
{

	const RESPONSE_DATA = 1;
	const RESPONSE_JSON = 2;
	const RESPONSE_ARRAY = 3;
	const RESPONSE_COLLECTION = 4;

	private $host;
	private $user;
	private $pass;

	public function __construct()
	{
		$this->host = env('JIRA_HOST');
		$this->user = env('JIRA_USER');
		$this->pass = env('JIRA_PASS');
	}

	private function sendRequest(string $endpoint, string $method = 'get', array $data = [], int $returnType = JiraApi::RESPONSE_DATA)
	{
		$url = $this->host . $endpoint;

		$httpResponse = Http::withBasicAuth($this->user, $this->pass)
			->withHeaders([
				'Content-Type: application/json'
			])
			->get($url, $data);

		if (empty($httpResponse['values']) || empty($httpResponse['issues'])) {
			$response = $httpResponse->body();
		}

		if (!empty($httpResponse['values'])) {
			$response = $httpResponse['values'];
		}

		if (!empty($httpResponse['issues'])) {
			$response = $httpResponse['issues'];
		}

		switch ($returnType) {
			case self::RESPONSE_DATA:
				$response = json_decode($response);
				break;
			case self::RESPONSE_JSON:
				$response = json_encode($response);
				break;
			case self::RESPONSE_ARRAY:
				$response = json_decode($response, true);
				break;
			case self::RESPONSE_COLLECTION:
				$response = collect(json_decode($response));
				break;
			default:
				$response = $httpResponse->body();
		}

		return $response;
	}

	public function getProjects(int $responseType = self::RESPONSE_DATA)
	{
		return $this->sendRequest("/rest/api/2/project", "GET", [], $responseType);
	}

	public function getBoardsByProject(array $data = [], int $responseType = self::RESPONSE_DATA)
	{
		return $this->sendRequest("/rest/agile/1.0/board", "GET", $data, $responseType);
	}

	public function getTaskBySprintId(string $sprintId, array $data = [], int $responseType = self::RESPONSE_DATA)
	{
		return $this->sendRequest("/rest/agile/1.0/sprint/{$sprintId}/issue", "GET", $data,  $responseType);
	}

	public function getOpenSprintsByBoardId(string $boardId = '73', array $data = [], int $responseType = self::RESPONSE_DATA)
	{
		return $this->sendRequest("/rest/agile/1.0/board/{$boardId}/sprint", 'GET', $data, $responseType);
	}

	public function getAllUsers(array $data = [], int $responseType = self::RESPONSE_DATA)
	{
		return $this->sendRequest("/rest/api/3/users/search", 'GET', $data, $responseType);
	}
}
