<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $table = 'books';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
