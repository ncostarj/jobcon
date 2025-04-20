<?php

namespace App\Models\Slack;

class Saudacao
{

	public $texto;
	public $icone;

	public function __construct(string $texto, string $icone)
	{
		$this->texto = $texto;
		$this->icone = $icone;
	}

	public function getTexto()
	{
		return $this->texto;
	}

	public function getIcone()
	{
		return $this->icone;
	}
}
