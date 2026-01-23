<?php

namespace App\Domain\Jobs\Models;

use App\Domain\Shared\Traits\Uuid;
use App\Models\User;
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

	protected $casts = [
		'saldo_anterior' => 'datetime:H:i',
		'saldo_atual' => 'datetime:H:i',
		'credito' => 'datetime:H:i',
		'debito' => 'datetime:H:i',
	];

	public function usuario() {
		return $this->belongsTo(User::class, 'user_id');
	}

}
