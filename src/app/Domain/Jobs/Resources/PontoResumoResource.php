<?php

namespace App\Domain\Jobs\Resources;

use App\Domain\Shared\Common\MyCalendar;
use Illuminate\Support\Collection;

class PontoResumoResource
{
	public static function toArray(Collection $collection)
	{
			$summarize = [];
			foreach ($collection as $ponto) {
				$summarize[$ponto->categoria] ??= 0;
				$summarize[$ponto->categoria] += 1;

				$summarize['ajustes'] ??= 0;
				if ($ponto->pedir_ajuste) {
					$summarize['ajustes'] += 1;
				}

				$summarize['observacoes'] ??= 0;
				if (!empty($ponto->observacao)) {
					$summarize['observacoes'] += 1;
				}

				$summarize['total'] ??= 0;
				$summarize['total'] += 1;
			}

		return $summarize;
	}
}
