<?php

namespace App\Domain\Jobs\Resources;

use Illuminate\Support\Collection;

class ContrachequeResource
{
	private static function getCompetenciaExtenso($dataCompetencia, $tipo)
	{
		[$ano, $mes, $dia] = explode('-', $dataCompetencia);

		switch($mes) {
			case '01': $competenciaExtenso = 'Jan'; break;
			case '02': $competenciaExtenso = 'Fev'; break;
			case '03': $competenciaExtenso = 'Mar'; break;
			case '04': $competenciaExtenso = 'Abr'; break;
			case '05': $competenciaExtenso = 'Mai'; break;
			case '06': $competenciaExtenso = 'Jun'; break;
			case '07': $competenciaExtenso = 'Jul'; break;
			case '08': $competenciaExtenso = 'Ago'; break;
			case '09': $competenciaExtenso = 'Set'; break;
			case '10': $competenciaExtenso = 'Out'; break;
			case '11': $competenciaExtenso = 'Nov'; break;
			case '12': $competenciaExtenso = 'Dez'; break;
			default: $competenciaExtenso = '';
		}

		return "{$competenciaExtenso}/{$ano} {$tipo}";
	}

	public static function toArray(Collection $collection)
	{
		return $collection->map(function ($item) {
			return [
				'id' => $item->id,
				'competencia' => $item->competencia_formatted,
				'competencia_extenso' => self::getCompetenciaExtenso($item->competencia_formatted, $item->tipo),
				'salario_base' => $item->salario_base,
				'salario_base_formatado' => $item->salario_base_formatted,
				'salario_liquido' => $item->salario_liquido,
				'salario_liquido_formatado' => $item->salario_liquido_formatted,
				'total_vencimentos' => $item->total_vencimentos,
				'total_vencimentos_formatado' => $item->total_vencimentos_formatted,
				'total_descontos' => $item->total_descontos,
				'total_descontos_formatado' => $item->total_descontos_formatted,
				'total_liquido' => round($item->total_liquido, 2),
				'total_liquido_formatado' => $item->total_liquido_formatted,
				'comprovante' => $item->comprovante,
				'link' => route('jobs.contracheques.edit', ['contracheque' => $item->id]),
			];
		})->toArray();
	}
}
