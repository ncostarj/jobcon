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

	public function getDiaFormattedAttribute()
	{
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
		if(!$this->entrada) {
			return '00:00';
		}

		if(!$this->saida) {
			return '00:00';
		}

		$jornada = strtotime($this->saida->hora) - strtotime($this->entrada->hora);
		$jornada += strtotime($this->tempoIntervalo);

		return date('H:i', $jornada);
	}

	public function getDebitoAttribute($value)
	{
		$resultado = "00:00";

		if ($this->subtotalHoras == '00:00') {
			return $resultado;
		}

		$padrao = strtotime('09:00:00');
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

		$padrao = strtotime('09:00:00');
		$subtotal = strtotime($this->subtotalHoras);

		if ($subtotal > $padrao) {
			$resultado = gmdate('H:i', $subtotal - $padrao);
		}

		return $resultado;
	}

	public function getHorarioAlmocoSaidaAttribute($value)
	{
		return $this->entrada ? date('H:i', strtotime($this->entrada->hora) + (3600 * 4)) : '00:00:00';
	}

	public function getTempoIntervaloAttribute($valor)
	{
		if (!$this->almoco_retorno) {
			return '00:00';
		}

		$almoco_saida = strtotime($this->almoco_saida->hora);
		$almoco_retorno = strtotime($this->almoco_retorno->hora);
		$diferenca = $almoco_retorno - $almoco_saida;
		return gmdate('H:i', $diferenca - 3600);
	}

	public function getHorarioRetornoAttribute($valor)
	{
		$horario = $this->almoco_saida ? $this->almoco_saida->hora : $this->horario_almoco_saida;
		return  date('H:i', strtotime($horario) + 3600);
	}

	public function getHorarioJornadaAttribute($value)
	{
		$jornada = strtotime($this->entrada->hora) + strtotime('09:00:00');

		if ($this->tempoIntervalo != '00:00') {
			$jornada += strtotime($this->tempoIntervalo);
		}

		return date('H:i', $jornada);
	}

	public function getHorarioTotalJornadaAttribute($value)
	{
		if(!$this->saida) {
			return '00:00';
		}

		$jornadaPadrao = strtotime('09:00:00');
		$entrada = strtotime($this->entrada->hora);
		$intervalo = strtotime($this->tempoIntervalo);
		$saida = strtotime($this->saida->hora ?? $this->horario_jornada);
		$saidaTotal = $saida + $intervalo;
		$diferenca = $saidaTotal - $entrada;
		$diferenca2 = $diferenca > $jornadaPadrao ? $diferenca - $jornadaPadrao : $jornadaPadrao - $diferenca;

		switch (true) {
			case $diferenca > $jornadaPadrao:
				$tipo = '+';
				break;
			case $diferenca < $jornadaPadrao:
				$tipo = '-';
				break;
			case $diferenca == '00:00':
				$tipo = '';
				break;
			default:
				$tipo = '';
				break;
		};

		return "{$tipo}" . gmdate('H:i', $diferenca2);
	}
}
