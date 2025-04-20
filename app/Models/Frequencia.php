<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Frequencia extends Model
{
	use Uuid, HasFactory, SoftDeletes;

	protected $table = 'frequencias';

	protected $fillable = [
		'data',
		'saldo_anterior',
		'saldo_atual',
		'credito',
		'debito',
	];

	public function usuario() {
		return $this->belongsTo(User::class, 'user_id');
	}

}
