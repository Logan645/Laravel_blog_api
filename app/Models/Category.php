<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable =[ //指定哪些可以存(fillable)
        'name'
    ];
    //category有很多article
     public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
