<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Horario extends Model
{
	use Uuid, HasFactory, SoftDeletes;

	protected $table = 'horarios';

	protected $fillable = [
		'hora',
		'tipo',
		'observacao'
	];

	public function ponto()
	{
		return $this->belongsTo(Ponto::class, 'ponto_id');
	}

	public function getHoraFormattedAttribute($value) {
		return date('H:i', strtotime($this->hora));
	}
}
