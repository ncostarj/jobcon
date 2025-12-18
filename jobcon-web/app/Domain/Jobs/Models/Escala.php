<?php

namespace App\Models;

use App\Models\User;
use App\Domain\Shared\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Escala extends Model
{
	use Uuid, HasFactory, SoftDeletes;

	protected $table = 'escalas';

	protected $fillable = [
		'dia',
		'qtd_do_dia',
		'dia_equipe',
		'equipe'
	];

	protected $casts = [
		'dia' => 'date:Y-m-d',
	];

	public function integrantes() {
		return $this->hasMany(EscalaIntegrante::class,'escala_id');
	}
}
