<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Auth;

class CommentController extends Controller
{
    public function addReply($id)
    {
        $comment = Comment::findOrFail($id);

        $model = new Comment();
        $model->text = request('text');
        $model->user_id = Auth::user()->id;

        $comment->replies()->save($model);
        $model->user;
        $model->user->profile;

        return response()->json($model, 200);
    }

    public function like($id)
    {
        $comment = Comment::findOrFail($id);

        $likes = Auth::user()->comments_liked()->where('likeable_id', $comment->id)->first();

        if (!is_null($likes)) {
            $likes->pivot->delete();
        } else {
            Auth::user()->comments_liked()->save($comment);
        }

        return response()->json('Comentário curtido/descurtido com sucesso', 200);
    }
}
