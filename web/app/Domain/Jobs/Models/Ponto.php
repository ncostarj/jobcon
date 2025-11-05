<?php

namespace App\Domain\Jobs\Models;

use App\Domain\Shared\Traits\Uuid;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use function PHPSTORM_META\map;

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

	public function getIntervaloAttribute($value)
	{
		if (!$this->almoco_retorno) {
			return '00:00';
		}

		return gmdate('H:i', (strtotime($this->almoco_retorno->hora) - strtotime($this->almoco_saida->hora)));
	}

	public function getIntervaloTotalAttribute($value)
	{

		if ($this->intervalo == '00:00') {
			return '00:00';
		}

		return gmdate('H:i', strtotime($this->intervalo) - 3600);
	}

	public function getJornadaAttribute($value)
	{
		if (!$this->saida) {
			return '00:00';
		}

		return gmdate('H:i', strtotime($this->saida->hora) - strtotime($this->entrada->hora));
	}

	public function getJornadaTotalAttribute($value)
	{
		if ($this->jornada == '00:00' || !$this->almoco_saida || !$this->almoco_retorno || !$this->saida) {
			return '00:00';
		}

		$entradaTs = strtotime($this->entrada->hora);
		$almocoSaidaTs = strtotime($this->almoco_saida ? $this->almoco_saida->hora : '00:00');
		$almocoRetornoTs = strtotime($this->almoco_retorno ? $this->almoco_retorno->hora : '00:00');
		$saidaTs = strtotime($this->saida ? $this->saida->hora : '00:00');

		$antes_almoco = $almocoSaidaTs - $entradaTs;
		$depois_almoco = $saidaTs - $almocoRetornoTs;

		$jornadaPadrao = strtotime('08:00');
		$jornada =  $antes_almoco + $depois_almoco + strtotime($this->intervalo_total);
		$jornadaTotal = $jornada > $jornadaPadrao ? $jornada - $jornadaPadrao : $jornadaPadrao - $jornada;

		if ($jornada == $jornadaPadrao) {
			return '00:00';
		}

		$tipo = match (true) {
			$jornada > $jornadaPadrao => '+',
			$jornada < $jornadaPadrao => '-',
		};

		$totalFormatado = date('H:i', $jornadaTotal);

		return "{$tipo} {$totalFormatado}";
	}

	public function getHorarioAlmocoSaidaAttribute($value)
	{

		if (!$this->entrada) {
			return '00:00';
		}

		return date('H:i', (strtotime($this->entrada->hora) + strtotime('06:00')));
	}

	public function getHorarioAlmocoRetornoAttribute($value)
	{
		$horaSaida = !$this->almoco_saida ? strtotime($this->horario_almoco_saida)  : strtotime($this->almoco_saida->hora);
		return date('H:i', ( $horaSaida + strtotime('01:00')));
	}

	public function getHorarioSaidaAttribute($value)
	{

		if (!$this->entrada) {
			return '00:00';
		}

		return date('H:i', (strtotime($this->entrada->hora) + strtotime('09:00')));
	}
}
