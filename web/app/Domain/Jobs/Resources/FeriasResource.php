<?php

namespace App\Domain\Jobs\Resources;

use Illuminate\Support\Collection;

class FeriasResource
{
	public static function toArray(Collection $collection)
	{
		return $collection->map(function ($resource) {
			return [
				'id' => $resource->id,
				'inicio' => $resource->inicio->format('d/m/Y'),
				'fim' => $resource->fim->format('d/m/Y'),
				'qtd_dias' => $resource->qtd_dias,
				'ativo' => $resource->ativo ? 'Sim' : 'Não',
				'observacao' => $resource->observacao,
				'link' => route('jobs.ferias.edit', [ 'id' => $resource->id ])
			];
		});
	}
}
