<?php

namespace App\Resources;

use App\Models\Common\MyCalendar;

class ContrachequeResource
{

	protected $calendar;
	protected $resources;

	public function __construct($resources)
	{
		$this->calendar = new MyCalendar;
		$this->resources = $resources;
	}

	private function getCompetenciaExtenso($dataCompetencia)
	{
		[$ano, $mes, $dia] = explode('-', $dataCompetencia);
		return "{$this->calendar->getMes($mes)}/{$ano}";
	}

	public function toObject($resource)
	{
		$competencia = $resource->competencia->format('Y-m-d');
		return (object) [
			'id' => $resource->id,
			'competencia' => $competencia,
			'competencia_extenso' => $this->getCompetenciaExtenso($competencia),
			'tipo' => $resource->tipo,
			'salario_base' => $resource->salario_base,
			'salario_base_formatado' => $resource->salario_base_formatted,
			'salario_liquido' => $resource->salario_liquido,
			'salario_liquido_formatado' => $resource->salario_liquido_formatted,
			'total_vencimentos' => $resource->total_vencimentos,
			'total_vencimentos_formatado' => $resource->total_vencimentos_formatted,
			'total_descontos' => $resource->total_descontos,
			'total_descontos_formatado' => $resource->total_descontos_formatted,
			'total_liquido' => round($resource->total_liquido, 2),
			'total_liquido_formatado' => $resource->total_liquido_formatted,
			'comprovante' => $resource->comprovante,
			'link' => route('jobs.contracheques.edit', [ 'contracheque' => $resource->id ]),
		];
	}

	public function toArray()
	{
		return $this->resources->map(function ($resource) {
			return $this->toObject($resource);
		});
	}

	public function toJson()
	{
		return json_encode($this->toArray());
	}
}
