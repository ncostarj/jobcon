<?php

namespace App\Resources;

class FrequenciaResource {

	protected $resources;

	public function __construct($resources) {
		$this->resources = $resources;
	}

	public function toObject($resource) {

		if(empty($resource)) {
			return (object) [
				'id' => null,
				'saldo_anterior' => '00:00',
				'credito' => '00:00',
				'debito' => '00:00',
				'saldo_atual' => '00:00'
			];
		}

		return (object) [
			'id' => $resource->id,
			'saldo_anterior' => date('H:i', strtotime($resource->saldo_anterior)),
			'credito' => date('H:i', strtotime($resource->credito)),
			'debito' => date('H:i', strtotime($resource->debito)),
			'saldo_atual' => date('H:i', strtotime($resource->saldo_atual))
		];
	}

	public function toArray() {
		return $this->resources->map(function($resource){
			return $this->toObject($resource);
		});
		return $array;
	}

	public function toJson() {
		return json_encode($this->toArray());
	}
}
