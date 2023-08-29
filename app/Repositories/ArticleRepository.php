<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Helpers\UploadHelper;
use Illuminate\Support\Facades\Auth;
use HTMLPurifier_URIFilter_SafeIframe;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Auth\Access\AuthorizationException;

class ArticleRepository
{
    protected $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function getPaginate($perPage): Paginator
    {
        return $this->article->with('user', 'category')->latest()->paginate($perPage);
    }

    public function getAllArticles(): Collection
    {
        return Article::get();
    }

    public function find($articleId): Article
    {
        return Article::findORFail($articleId);
    }

    public function save(array $data): Article
    {
        $article = new Article();
        $article->title = $data['title'];
        $article->body = $data['body'];
        $article->category_id = $data['category_id'];
        $article->user_id = $data['user_id'];
        $article->save();
        return $article;
    }

    public function update($articleId, array $data): Article
    {
        $article = Article::findOrFail($articleId);
        $article->update($data);
        return $article;
    }

    public function delete($articleId)
    {
        $article = Article::find($articleId);
        if ($article) {
            $article->delete();
        }
    }

}
