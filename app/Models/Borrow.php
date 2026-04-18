<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function tool() {
        return $this->belongsTo(Tool::class);
    }

    public function users() {
        return $this->hasMany(User::class);
    }
}
