<?php

namespace App\Models\Jira;

use App\Models\Common\BaseGateway;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\Jira\Resources\TarefaResource;
use App\Models\Jira\Resources\ProjetoResource;
use App\Models\Jira\Resources\StatusResource;
use App\Models\Jira\Resources\UsuarioResource;

class JiraService extends BaseGateway
{

	protected $projetos;

	public function __construct()
	{
		parent::__construct(env('JIRA_HOST'), ['username' => env('JIRA_USER'), 'password' => env('JIRA_PASS')]);
	}

	public function getSprints(int $responseType = self::RESPONSE_DATA, bool $debug = false)
	{
		$boardId = env('JIRA_BOARD', 73);
		$response = $this->sendRequest("/rest/agile/1.0/board/{$boardId}/sprint", "GET", ['state' => 'active'], $responseType, $debug);
		return $this->defaultReponse(200, 'Dados retornados com sucesso', $response);
	}

	public function getProjetos(int $responseType = self::RESPONSE_DATA, bool $debug = false)
	{
		$response = $this->sendRequest("/rest/api/3/project/search", "GET", [], $responseType, $debug);
		$response['data'] = ProjetoResource::toArray($response['data']->values);
		return $response;
		// return $this->defaultReponse(200, 'Dados retornados com sucesso', ProjetoResource::toArray($response));
	}

	public function getUsuarios(string $projetoKeys, int $responseType = self::RESPONSE_DATA, bool $debug = false)
	{
		$response = $this->sendRequest("/rest/api/3/user/assignable/multiProjectSearch", "GET", ['query' => 'query', 'projectKeys' => $projetoKeys], $responseType, $debug);
		return $this->defaultReponse(200, 'Dados retornados com sucesso', $response);
	}

	public function getAllUsuarios(int $responseType = self::RESPONSE_DATA, bool $debug = false)
	{
		$response = $this->sendRequest("/rest/api/3/users/search", 'GET', [
			'maxResults' => 200,
			'expand' => 'renderedFields'
		], $responseType, $debug);
		$response['data'] = UsuarioResource::toArray($response['data']);
		return $response;
	}


	public function searchStatuses(array $data, int $responseType = self::RESPONSE_DATA, bool $debug = false)
	{
		$response = $this->sendRequest("/rest/api/3/statuses/search", 'GET', [
			'maxResults' => 200,
			'expand' => 'usages',
			'projectId' => $data['projetoId']
		], $responseType, $debug);
		$response['data'] = StatusResource::toArray($response['data']->values, $data['projetoId']);
		return $response;
	}

	public function searchTarefas(array $data, int $responseType = self::RESPONSE_DATA, bool $debug = false)
	{
		$projetos = [];

		if(!in_array($data['projects'],[ 'OCT', 'SOCTO' ])) {
			$projetos = $data['projects'];
		}

		if(in_array($data['projects'],[ 'OCT', 'SOCTO' ])) {
			$projetos = implode(',', array_merge([$data['projects']], array_diff([ 'OCT', 'SOCTO' ], [$data['projects']])));
		}

		// and status not in (Finalizado)
		$jql = "project IN ({$projetos})";

		if(!empty($data['usuario'])) {
			$jql .= " and worklogAuthor = '{$data['usuario']}'";
			// $jql .= " and assignee = '{$data['usuario']}'";
		}

		if(!empty($data['status'])) {
			$jql .= " and status = '{$data['status']}'";
		}

		if (empty($data['status'])) {
			$jql .= " and status IN ('Finalizado','Aguardando Release', 'CODE REVIEW', 'Em Análise', 'EM DESENVOLVIMENTO', 'Para Fazer', 'Pendente', 'QA', 'Reaberto', 'Teste Dev','Backlog','Done','Em Análise','In Progress')";
		}// status 'Finalizado',

		// $jql .= ' ORDER BY status ASC';
		$jql .= '  and created >= -60d order by created DESC';

		// and worklogDate >= '{$data['data_inicio']}' and worklogDate <= '{$data['data_fim']}'
		// dd('---');
		// $jql = "project in ('Proj - OCTO', 'Solicitações OCTO') and assignee = 6197a0096d002b006b37e78b and worklogDate >= '2025-01-13' and worklogDate <= '2025-01-17'";

		$response = $this->sendRequest("/rest/api/2/search/jql", 'GET', [
			'jql' => $jql,
			'fields' => '*all',
			'maxResults' => 200
			// 'expand'=> 'renderedFields'
		], $responseType, $debug);

		Log::info(compact('jql','projetos', 'data', 'response'));

		$response['data'] = TarefaResource::toArray($response['data']->issues??[]);
		return $response;
	}

	public function storeTarefa(array $data, int $responseType = self::RESPONSE_DATA, bool $debug = false) {



        $data = [
            "fields" => [
                // 'customfield_10150' => $camposExtra,
                "project" => [ "id" => $this->project], // id do projeto
                "priority" => [ "name" => $tarefa['prioridade']], // prioridade
                "issuetype" => [ "id" => $tipoChamados[$tarefa['tipoItem']] ], // tipo do item
                "summary" => $tarefa['resumo'], // resumo da task
                "description" => $tarefa['descricao'], // descricao da task

            ],
        ];

		$response = $this->sendRequest("/rest/api/3/issue", 'POST', $data, $responseType, $debug);
		// $response['data'] = TarefaResource::toArray($response['data']->issues??[]);
		return $response;
	}

	public function storeWorklog($issueIdOrKey, array $data, int $responseType = self::RESPONSE_DATA, bool $debug = false) {
		$response = $this->sendRequest("/rest/api/3/issue/{$issueIdOrKey}/worklog", 'POST', [
		], $responseType, $debug);
		return $response;
	}
}
