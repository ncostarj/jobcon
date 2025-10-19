<?php

namespace App\Domain\Jobs\Resources;

use App\Domain\Shared\Common\MyCalendar;
use Illuminate\Support\Collection;

class PontoResource
{
	public static function toArray(Collection $collection)
	{
		$calendar = new MyCalendar();
		return $collection->map(function ($item) use ($calendar) {
			return [
				'dia' => $item->dia->format('d/m/Y'),
				'diaSemana' => $calendar->getDiaSemana($item->dia->format('w')),
				'observacao' => $item->observacao,
				'categoria' => $item->categoria == 'home_office' ? 'Home Office' : 'Presencial',
				'entrada' => $item->entrada ? $item->entrada->horaFormatted : '-',
				'almoco_saida' => $item->almoco_saida ? $item->almoco_saida->horaFormatted : '-',
				'almoco_retorno' => $item->almoco_retorno ? $item->almoco_retorno->horaFormatted : '-',
				'saida' => $item->saida ? $item->saida->horaFormatted : '-',
				'horario_almoco_saida' => $item->horario_almoco_saida,
				'horario_intervalo' => $item->tempo_intervalo,
				'horario_retorno' => $item->horario_retorno,
				'horario_jornada' => $item->horario_jornada,
				'horario_total_jornada' => $item->horario_total_jornada,
				'horario_saida' => $item->horario_saida,
				'credito' => $item->credito ? $item->credito : '-',
				'debito' => $item->debito ? $item->debito : '-',
				'pedir_ajuste' => $item->pedir_ajuste,
				'ajuste_finalizado' => $item->ajuste_finalizado,
				'link_ajuste' => route('jobs.pontos.edit', ['ponto' => $item->id]),
			];
		})->toArray();
	}
}
