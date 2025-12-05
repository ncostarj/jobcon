<?php

namespace App\Domain\Jobs\Resources;

use App\Domain\Shared\Common\MyCalendar;
use Illuminate\Support\Collection;

class PontoMesResource
{
	public static function toArray(Collection $collection)
	{
		$myCalendar = new MyCalendar();
		return $collection->map(function($item) use($myCalendar) {
			return [
				'numero' => $item->mes,
				'nome' => $myCalendar->getMes($item->mes),
				'ano' => $item->ano,
				'mes_ano' => "{$item->ano}-{$item->mes}",
			];
		})->toArray();



		return $summarize;
	}
}
