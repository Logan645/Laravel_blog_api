<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Article;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Article $article)
    {
        return $user->id === $article->user_id;
    }

    // ... 其他授權方法 ...
}
