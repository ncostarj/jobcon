<?php

namespace App\Domain\Jobs\Services;

use App\Domain\Jobs\Services\BaseService;
use App\Domain\Shared\Common\MyCalendar;

class CalendarioService extends BaseService
{
	protected $myCalendar;

	public function __construct() {
		$this->myCalendar = new MyCalendar();
	}

	public function showCalendario() {
		return $this->defaultReponse(200,'Dados retornados com sucesso',$this->myCalendar->getData());
	}

	public function getCurrentWeek($dados) {
		return $this->defaultReponse(200,'Dados retornados com sucesso',$this->myCalendar->getCurWeekInterval($dados));
	}

	public function getFeriados() {
		return $this->defaultReponse(200,'Dados retornados com sucesso',$this->myCalendar->getFeriados());
	}
}
