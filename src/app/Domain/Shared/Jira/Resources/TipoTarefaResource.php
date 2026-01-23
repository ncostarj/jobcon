<?php

namespace App\Models\Jira\Resources;

class TipoTarefaResource
{

	public static function toObject($resource)
	{
		return empty($resource) ? $resource : (object) [
			'id' => $resource->id,
			'icone' => $resource->iconUrl,
			'nome' => $resource->name,
		];
	}
}
