<?php

namespace App\Models;

use App\Traits\Uuid;
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
