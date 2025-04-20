<?php

namespace App\Models\Jira\Resources;

use Illuminate\Support\Facades\Log;

class StatusResource
{
	public static function toObject($resource)
	{
		return empty($resource) ? $resource : (object) [
			'id' => $resource->id,
			'icone' => $resource->iconUrl??'',
			'nome' => $resource->name,
		];
	}

	public static function toArray($resources, $projetoId)
	{
		return array_values(array_map(function ($resource) {
			return self::toObject($resource);
		}, array_filter($resources, function ($resource) use ($projetoId) {
			return !collect($resource->usages)->where('project.id', $projetoId)->isEmpty();
		})));
	}
}
