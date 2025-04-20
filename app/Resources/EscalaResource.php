<?php

namespace App\Resources;

use App\Models\Common\MyCalendar;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class EscalaResource
{

	protected $calendar;
	protected $resources;

	public function __construct($resources)
	{
		$this->calendar = new MyCalendar;
		$this->resources = $resources;
	}

	public function toObject($resource)
	{
		$dia = strtotime($resource['dia']);
		return (object) [
			'id' => $resource['id'],
			'dia' => $resource['dia'],
			'dia_formatado' => date('d/m/Y', $dia),
			'dia_semana' => $this->calendar->getDiaSemana(date('N', $dia)),
			'usuario' => $resource['usuario'],
		];
	}

	public function toArray() {
		$today =  date('Y-m-d');
		$data = [];
		foreach($this->resources as $resource) {
			$resource = $this->toObject($resource);
			$date = date('Y-m-d', strtotime($resource->dia));
			if($date < $today) {
				continue;
			}
			$month = date('m', strtotime($resource->dia));
			$data[$month]['mes']= $this->calendar->getMes($month);
			$data[$month]['dias'][] = $resource;
		}
		return $data;
	}
}
