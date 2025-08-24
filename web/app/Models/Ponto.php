<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ponto extends Model
{
	use Uuid, HasFactory, SoftDeletes;

	protected $table = 'pontos';

	protected $fillable = [
		'dia',
		'categoria',
		'pedir_ajuste',
		'ajuste_finalizado',
		'observacao'
	];

	protected $casts = [
		'dia' => 'date:Y-m-d'
	];

	public function usuario()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function getDiaFormattedAttribute() {
		return $this->dia->format('Y-m-d');
	}

	public function horarios()
	{
		return $this->hasMany(Horario::class, 'ponto_id')->orderBy('hora', 'asc');
	}

	public function getEntradaAttribute($value)
	{
		return $this->horarios->where('tipo', 'entrada')->first();
	}

	public function getAlmocoSaidaAttribute($value)
	{
		return $this->horarios->where('tipo', 'almoco_saida')->first();
	}

	public function getAlmocoRetornoAttribute($value)
	{
		return $this->horarios->where('tipo', 'almoco_retorno')->first();
	}

	public function getSaidaAttribute($value)
	{
		return $this->horarios->where('tipo', 'saida')->first();
	}

	public function getSubtotalHorasAttribute($value)
	{
		$horaEntrada = $this->entrada ? $this->entrada->hora : "00:00";
		$horaAlmocoSaida = $this->almoco_saida ? $this->almoco_saida->hora : "00:00";
		$horaAlmocoRetorno = $this->almoco_retorno ? $this->almoco_retorno->hora : "00:00";
		$horaSaida = $this->saida ? $this->saida->hora : "00:00";

		// if ($horaAlmocoSaida == '00:00' || $horaAlmocoRetorno == '00:00' || $horaSaida == '00:00') {
		// 	return '00:00';
		// }

		if(in_array($this->dia, ['2024-12-24','2024-12-31'])) {
			return "00:00";
		}

		$diferenca1 = strtotime($horaAlmocoSaida) - strtotime($horaEntrada);
		$diferenca2 = strtotime($horaSaida) - strtotime($horaAlmocoRetorno);
		return gmdate('H:i', $diferenca1 + $diferenca2);
	}

	public function getDebitoAttribute($value)
	{
		$resultado = "00:00";

		if ($this->subtotalHoras == '00:00') {
			return $resultado;
		}

		$padrao = strtotime('08:00:00');
		$subtotal = strtotime($this->subtotalHoras);

		if ($subtotal < $padrao) {
			$resultado = gmdate('H:i', $padrao - $subtotal);
		}

		return $resultado;
	}

	public function getCreditoAttribute($value)
	{
		$resultado = "00:00";

		if ($this->subtotalHoras == '00:00') {
			return $resultado;
		}

		$padrao = strtotime('08:00:00');
		$subtotal = strtotime($this->subtotalHoras);

		if ($subtotal > $padrao) {
			$resultado = gmdate('H:i', $subtotal - $padrao);
		}

		return $resultado;
	}

	public function getHorarioSaidaAttribute($value)
	{
		$resultado = "00:00";

		// if($this->entrada && empty($this->almocoRetorno)) {
		// 	$resultado = strtotime('+ 7 hours', strtotime($this->entrada->hora));
		// 	$resultado = gmdate('H:i', $resultado);
		// }

		// if($this->subtotalHoras == '00:00') {
		// 	return $resultado;
		// }

		// $padrao = strtotime('08:00:00');
		// $subtotal = strtotime($this->subtotalHoras);

		// if ($subtotal > $padrao) {
		// 	$resultado = gmdate('H:i', $subtotal - $padrao);
		// }

		return $resultado;
	}
}
