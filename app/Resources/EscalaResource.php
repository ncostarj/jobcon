<?php

namespace App\Resources;

use App\Models\Common\MyCalendar;

class EscalaResource
{

	protected $calendar;
	protected $resources;

	public function __construct($resources)
	{
		$this->calendar = new MyCalendar;
		$this->resources = $resources;
	}

	private function getEscalacao($escalados)
	{
		return array_map(function ($escalado) {
			return $escalado['nome'];
		}, $escalados->toArray());
	}

	public function toObject($resource)
	{
		return (object) [
			'id' => $resource['id'],
			'dia' => $resource['dia']->format('Y-m-d'),
			'mes' => $resource['dia']->format('m'),
			'dia_formatado' => $resource['dia']->format('d/m/Y'),
			'dia_semana' => $this->calendar->getDiaSemana($resource['dia']->format('N')),
			'dia_equipe' => $resource['dia_equipe'],
			'time' => $resource['equipe'],
			'escalacao' => $this->getEscalacao($resource['integrantes'])
		];
	}

	public function toArray()
	{

		$escalas = [];

		$inicio = date('Y-m-d', strtotime('monday this week'));
		$fim = date('Y-m-d', strtotime('friday this week'));

		foreach ($this->resources as $resource) {
			$escalas[] = $this->toObject($resource);
		}

		usort($escalas, function ($a, $b) {
			return $a->dia <=> $b->dia;
		});

		foreach ($escalas as $item) {
			usort($item->escalacao, function ($a, $b) {
				return $a <=> $b;
			});
		}

		foreach ($escalas as $escala) {
			if($escala->dia >= $inicio && $escala->dia <= $fim) {
				$data[] = $escala;
			}
		}

		return $data;
	}
}
