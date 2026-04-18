<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $table = 'tools';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function toolmarks()
    {
        return $this->hasMany(Mark::class);
    }

    public function borrows() {
        return $this->hasMany(Borrow::class);
    }
}
