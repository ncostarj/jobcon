<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ferias extends Model
{
	use Uuid, HasFactory, SoftDeletes;

	protected $table = 'ferias';

	protected $fillable = [
		'inicio',
		'fim',
		'qtd_dias',
		'observacao'
	];

	protected $casts = [
		'inicio' => 'date:Y-m-d',
		'fim' => 'date:Y-m-d'
	];

	public function usuario() {
		return $this->belongsTo(User::class, 'user_id');
	}

	public function getAtivoAttribute() {
		$hoje = date('Y-m-d');
		$inicio = $this->inicio->format('Y-m-d');
		$fim = $this->fim->format('Y-m-d');
		return $hoje >= $inicio && $hoje <= $fim;
	}
}
