<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'body',
        'category_id'
    ];

    protected $with =[
        'tags',
        'category',
    ];
    //article有一個category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAbcTitleAttribute() //中間Abctitle是自己命名的
    {
        return $this->title.'-ooooooo';
    }
    // Article::find(2)->abc_tilte //操作資料庫時就要使用abc_tilte
    //得"ggg-ooooooo"

    public function getTitleAttribute($value)
    {
        return $value .'-cccc';
    }
    //Article::find(2)->title
    //"ggg-cccc"

    public function scopenewArticles($query)
    {
        return $query->where('id', '>=', 5)->get();
    }
    // Article::newArticles()
}
