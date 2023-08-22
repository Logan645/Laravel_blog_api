<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'article_id', 'user_id'];

    // 定義與文章的關聯
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // 定義與使用者的關聯
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
