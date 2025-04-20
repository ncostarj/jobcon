<?php

namespace App\Models\Jira\Resources;

class UsuarioResource
{

	public static function toObject($resource)
	{
		return empty($resource) ? $resource : (object) [
			'id' => $resource->accountId,
			'email' => $resource->emailAddress??'',
			'icone' => $resource->avatarUrls,
			'nome' => $resource->displayName,
		];
	}

	public static function toArray($resources)
	{

		if (!empty($resources->values)) {
			$resources = $resources->values;
		}

		$list = [];
		foreach ($resources as $resource) {
			if ($resource->accountType != 'atlassian' || $resource->active == false) {
				continue;
			}
			$list[] = [
				'id' => $resource->accountId,
				'nome' => $resource->displayName,
				'email' => $resource->emailAddress??'',
				'icones' => $resource->avatarUrls
			];
		}
		return $list;
	}

	public static function toCollection($resource)
	{
		return collect(self::toArray($resource));
	}
}
