<?php

namespace App\Domain\Jobs\Models;

use App\Domain\Shared\Traits\Uuid;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empresa extends Model
{
	use Uuid, HasFactory, SoftDeletes;

	protected $table = 'empresas';

	protected $fillable = [
		'razao_social',
		'estabelecimento',
		'cnpj',
	];
}
