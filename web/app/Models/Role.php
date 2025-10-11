<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use Uuid, HasFactory, SoftDeletes;

    protected $table = 'roles';

    protected $fillable = [
        'nome'
    ];

    public function actions() {
        return $this->belongsToMany(Action::class)->whereNull('actions.action_id')->orderBy('ordem', 'asc');
    }
}
