<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    protected $fillable = [
        'collection_name'
    ];

    function user() {
        return $this->hasMany(User::class, 'collection_id');
    }
}
