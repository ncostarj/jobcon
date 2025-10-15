<?php

namespace App\Domain\Jobs\Models;

use App\Domain\Shared\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Action extends Model
{
    use Uuid, HasFactory, SoftDeletes;

    protected $table = 'actions';

    protected $fillable = [
        'texto',
        'url',
        'route_name',
        'ordem',
    ];

    public function subactions() {
        return $this->hasMany(Action::class);
    }
}
