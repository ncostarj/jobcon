<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contracheque extends Model
{
	use Uuid, HasFactory, SoftDeletes;

	protected $table = 'contracheques';

	protected $fillable = [
		'competencia',
		'tipo',
		'salario_base',
		'salario_liquido',
		'total_vencimentos',
		'total_descontos',
		'arquivo'
	];

	protected $casts = [
		'competencia' => 'date:Y-m-d',
	];

	public function usuario()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'empresa_id');
	}

	private function formatMoney($valor, $qtdCasasDecimais = 2, $separadorDecimal = ',', $separadorMilhar = '.') {
		return number_format($valor, 2, ',', '.');
	}

	public function getSalarioBaseFormattedAttribute()
	{
		return 'R$ ' . $this->formatMoney($this->salario_base);
		// number_format($this->salario_base, 2, ',', '.');
	}

	public function getSalarioLiquidoFormattedAttribute()
	{
		return 'R$ ' . $this->formatMoney($this->salario_liquido);
		// number_format($this->salario_liquido, 2, ',', '.');
	}

	public function getTotalVencimentosFormattedAttribute()
	{
		return 'R$ ' . $this->formatMoney($this->total_vencimentos);
		// return 'R$ ' . number_format($this->total_vencimentos, 2, ',', '.');
	}

	public function getTotalDescontosFormattedAttribute()
	{
		return 'R$ ' . $this->formatMoney($this->total_descontos);
		// return 'R$ ' . number_format($this->total_descontos, 2, ',', '.');
	}

	public function getTotalLiquidoAttribute() {
		return $this->total_vencimentos - $this->total_descontos;
	}

	public function getTotalLiquidoFormattedAttribute() {
		return 'R$ ' . $this->formatMoney($this->total_liquido);
		// return 'R$' . number_format($this->total_liquido, 2, ',', '.');
	}

	public function listarAnos(int $ano) {
		return $this->when($ano, function($query, $ano) {
			$query->where('ano', $ano);
		});
	}
}
