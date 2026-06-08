<?php

namespace App\Models\Jira\Resources;

use Illuminate\Support\Facades\Log;

class TarefaResource
{
	public static function toArray($resources)
	{
		return array_map(function ($resource) {
			$fields = $resource->fields;
			$projeto = ProjetoResource::toObject($fields->project);

			$tarefa = [
				'id' => $resource->id,
				'key' => $resource->key,
				'resumo' => $fields->summary,
				'descricao' => $fields->description,
				'tipo' => TipoTarefaResource::toObject($fields->issuetype),
				'status' => StatusResource::toObject($fields->status),
				'prioridade' => PrioridadeResource::toObject($fields->priority),
				'relator' => UsuarioResource::toObject($fields->reporter),
				'responsavel' => UsuarioResource::toObject($fields->assignee),
				// 'equipe' => $fields->customfield_10097 (array) team octo
				// 'tipo_demanda' => $fields->customfield_10096 (array) // backend frontend
				'projeto' => $projeto,
				'comentarios' => !empty($fields->comment) ? $fields->comment->comments : [],
				'data_entrega' => $fields->duedate,
				'sp' => $fields->customfield_10026??0,
				'sp_estimativa' => $fields->customfield_10016??0,
				// 'projetos' => ,
				// 'comentarios' => ,
				// 'worklogs' => !empty($fields->worklog) ? RegistroTrabalhoResource::toArray($fields->worklog->worklogs, $tarefa) : [],
			];
			$tarefa['worklogs'] = !empty($fields->worklog) ? RegistroTrabalhoResource::toArray($fields->worklog->worklogs, $tarefa) : [];
			return $tarefa;
		}, $resources);
	}

	public static function toCollection($resource)
	{
		return collect(self::toArray($resource));
	}
}
