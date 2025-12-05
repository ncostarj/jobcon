<?php

namespace App\Http\Controllers;

use App\Models\JiraService;
use Illuminate\Http\Request;

class JiraController extends Controller
{
    public function index(Request $request, JiraService $jiraService) {
		return redirect('jira/kanban');
    }

    public function kanban(Request $request, JiraService $jiraService) {
        $projetos = $jiraService->getProjects(JiraService::RESPONSE_COLLECTION);
        return view('jobs.jira.kanban', compact('projetos'));
    }

    public function tarefas(Request $request, JiraService $jiraService) {
        $projetos = $jiraService->getProjects(JiraService::RESPONSE_COLLECTION);
        return view('jobs.jira.tarefas', compact('projetos'));
    }

    public function getUsuarios(Request $request, JiraService $jiraService) {
        return $jiraService->getUsers([], JiraService::RESPONSE_JSON);
    }

    public function getProjetos(Request $request, JiraService $jiraService) {
        return $jiraService->getProjects(JiraService::RESPONSE_JSON);
    }

    public function getPaineis(Request $request, JiraService $jiraService) {
        return $jiraService->getBoards($request->input('projectId'), JiraService::RESPONSE_JSON);
    }

    public function getSprints(Request $request, JiraService $jiraService) {
        return $jiraService->getSprints($request->input('boardId'), JiraService::RESPONSE_JSON);
    }

    public function getTarefas(Request $request, JiraService $jiraService) {
        return $jiraService->getTasks($request->input('sprintId'), $request->except('sprintId'), JiraService::RESPONSE_JSON);
    }
}
