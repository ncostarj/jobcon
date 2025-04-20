<?php

namespace App\Models\Jira\Resources;

class RegistroTrabalhoResource {

	private static function formatDate($date) {
		$dataFormatada = preg_replace("/([0-9]{4})-([0-9]{2})-([0-9]{2})(.*)/",'$3/$2/$1',$date);
		return $dataFormatada??'';
	}

	private static function getTime(float $float)
	{
		$hour = (int) floor($float);
		$decimal = fmod($float, 1);
		$minutesFull = $decimal * 60;
		$minutes = (int) floor($minutesFull);
		$timeSpent = '';

		if($hour > 0) {
			$timeSpent .= "{$hour}h ";
		}

		if($minutes > 0) {
			$timeSpent .= "{$minutes}m";
		}

		return $timeSpent;
	}

	public static function toArray($resources, $tarefa) {
		$registros = [];

		foreach($resources as $item) {

			if($item->timeSpentSeconds == 0) {
				continue;
			}

			$registros[] = [
				'id' => $item->id,
				'tarefa'=> $tarefa,
				'autor' => UsuarioResource::toObject($item->author),
				'issue_id' => $item->issueId,
				'time_spent' => $item->timeSpent,
				'time_spent_seconds' => $item->timeSpentSeconds,
				'time_spent_hours' => self::getTime($item->timeSpentSeconds/3600),
				'comentario' => $item->comment ?? '',
				'criado_em' => $item->created,
				'atualizado_em' => $item->updated,
				'iniciado_em' => $item->started,
				'iniciado_em_formatado' => self::formatDate($item->started)
			];
		}

		return $registros;
	}
}
