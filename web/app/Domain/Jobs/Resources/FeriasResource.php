<?php

namespace App\Resources;

class FeriasResource
{

	protected $resources;

	public function __construct($resources)
	{
		$this->resources = $resources;
	}

	public function toObject($resource) {
		return (object) [
			'id' => $resource->id,
			'inicio' => $resource->inicio->format('d/m/Y'),
			'fim' => $resource->fim->format('d/m/Y'),
			'qtd_dias' => $resource->qtd_dias,
			'ativo' => $resource->ativo ? 'Sim' : 'Não',
			'observacao' => $resource->observacao,
			'link' => route('jobs.ferias.edit', [ 'ferias' => $resource->id ])
		];
	}

	public function toArray()
	{
		return $this->resources->map(function($resource){
			return $this->toObject($resource);
		});
	}

	public function toJson()
	{
		return json_encode($this->toArray());
	}
}
