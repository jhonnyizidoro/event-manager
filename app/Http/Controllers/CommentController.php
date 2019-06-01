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
}
