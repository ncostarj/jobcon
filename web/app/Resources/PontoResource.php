<?php

namespace App\Resources;

use App\Models\Common\MyCalendar;
use App\Models\Ponto;
use App\Models\Horario;
use Illuminate\Support\Facades\Log;

class PontoResource
{

	protected $calendar;
	protected $resources;

	public function __construct($resources)
	{
		$this->resources = $resources;
		$this->calendar = new MyCalendar();
	}

	public function toObject($resource)
	{
		return (object) [
			'dia' => $resource->dia->format('d/m/Y'),
			'diaSemana' => $this->calendar->getDiaSemana($resource->dia->format('w')),
			'observacao' => $resource->observacao,
			'categoria' => $resource->categoria == 'home_office' ? 'Home Office' : 'Presencial',
			'entrada' => $resource->entrada ? $resource->entrada->horaFormatted : '-',
			'almoco_saida' => $resource->almoco_saida ? $resource->almoco_saida->horaFormatted : '-',
			'almoco_retorno' => $resource->almoco_retorno ? $resource->almoco_retorno->horaFormatted : '-',
			'saida' => $resource->saida ? $resource->saida->horaFormatted : '-',
			'horario_saida' => $resource->horario_saida,
			'credito' => $resource->credito ? $resource->credito : '-',
			'debito' => $resource->debito ? $resource->debito : '-',
			'pedir_ajuste' => $resource->pedir_ajuste,
			'ajuste_finalizado' => $resource->ajuste_finalizado,
			'link_ajuste' => route('jobs.pontos.edit', [ 'ponto' => $resource->id ]),
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
