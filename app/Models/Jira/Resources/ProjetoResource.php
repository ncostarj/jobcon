<?php

namespace App\Models\Jira\Resources;

use Illuminate\Support\Facades\Log;

class ProjetoResource
{
	public static function toObject($resource) {
		return [
			'id' => $resource->id,
			'key' => $resource->key,
			'nome' => $resource->name,
			'icones' => $resource->avatarUrls
		];
	}

	public static function toArray($resources)
	{
		return array_map(function($resource) {
			return self::toObject($resource);
		}, $resources);

		// if (!empty($resources->values)) {
		// 	$resources = $resources->values;
		// }

		// $list = [];
		// foreach ($resources as $resource) {
		// 	$list[] = [
		// 		'id' => $resource->id,
		// 		'key' => $resource->key,
		// 		'nome' => $resource->name,
		// 		'icones' => $resource->avatarUrls
		// 	];
		// }
		// return $list;
		// Log::info($resources);
		// return [];
	}

	public static function toCollection($resource)
	{
		return collect(self::toArray($resource));
	}


}
