<?php

namespace App\Models\Jira\Resources;

use Illuminate\Support\Facades\Log;

class PrioridadeResource {
	public static function toObject($resource)
	{
		return empty($resource) ? $resource : (object) [
			'id' => $resource->id,
			'icone' => $resource->iconUrl,
			'nome' => $resource->name,
		];
	}
}
