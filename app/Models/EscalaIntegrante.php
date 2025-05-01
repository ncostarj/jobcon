<?php

namespace App\Models;

use App\Models\User;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class EscalaIntegrante extends Model
{
	use Uuid, HasFactory, SoftDeletes;

	protected $table = 'escalas_integrantes';

	protected $fillable = [
		'nome'
	];

	public function escala() {
		return $this->belongsTo(Escala::class, 'escala_id');
	}

	public function usuario()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
