<?php

namespace App\Models;

class Classe2 {

    const RESPONSE_DATA = 1;
    const RESPONSE_JSON = 2;
    const RESPONSE_ARRAY = 3;
    const RESPONSE_COLLECTION = 4;

    protected $jiraApi;

    public function __construct() {
        $this->jiraApi = new JiraApi();
    }

    public function getUsers(array $data = [], int $responseType = JiraService::RESPONSE_DATA){
        return $this->jiraApi->getAllUsers($data, $responseType);
    }

    public function getProjects(int $responseType = JiraService::RESPONSE_DATA) {
        return $this->jiraApi->getProjects($responseType)->where('archived',false);
    }

    public function getBoards(string $projectIdOrKey = 'OCT', int $responseType = JiraService::RESPONSE_DATA) {
        return $this->jiraApi->getBoardsByProject([ 'projectKeyOrId' => $projectIdOrKey ], $responseType);

    }

    public function getSprints(string $boardId = '73', int $responseType = JiraService::RESPONSE_DATA) {
        return $this->jiraApi->getOpenSprintsByBoardId($boardId, [ 'state' => 'active' ], $responseType); //'active,future, closed'

    }

    public function getTasks(string $sprintId = '1008', array $data = [], int $responseType = JiraService::RESPONSE_DATA){
        return $this->jiraApi->getTaskBySprintId($sprintId, $data, $responseType);
    }


}
