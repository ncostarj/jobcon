<?php

namespace App\Resources;

class FrequenciaResource {

	protected $resources;

	public function __construct($resources) {
		$this->resources = $resources;
	}

	public function toObject($resource) {
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
