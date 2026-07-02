<?php

namespace App\Services;

use App\Models\Jira\JiraService;

class TarefaService
{

	protected $jiraService;

	public function __construct() {
		$this->jiraService = new JiraService();
	}

	// public function listar(int $sprintId) {
	// 	return $this->jiraService->getTaskBySprintId($sprintId);
	// }

}
