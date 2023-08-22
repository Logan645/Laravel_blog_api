<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
        // 新增留言
        $data = $request->validate([
            'content' => 'required|string',
            'article_id' => 'required|exists:articles,id',
            // 如果留言需要使用者登入才能發表，可以加入以下驗證：
            'user_id' => 'required|exists:users,id',
        ]);

        $comment = Comment::create($data);

        return response()->json($comment, 201);
    }

    public function update(Request $request, Comment $comment)
    {
        // 更新留言
        $data = $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update($data);

        return response()->json($comment, 200);
    }

    public function destroy(Comment $comment)
    {
        // 刪除留言
        $comment->delete();

        return response()->json(null, 204);
    }
}
