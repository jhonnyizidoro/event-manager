<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Follow;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Requests\Post\NewPost as NewPostRequest;
use Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewPostRequest $request)
    {
        $request->merge(['user_id' => Auth::user()->id]);
        $post = Post::create($request->all());
        $post->user;

        return json($post, 'Post salvo.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

    public function addComment($id, Request $request)
    {
        try {
            $post = Post::findOrFail($id);

            $comment = new Comment();
            $comment->text = $request->post('comment');
            $comment->user_id = Auth::user()->id;

            $post->comments()->save($comment);
            $comment->user->profile;

            return response()->json($comment, 200);
        } catch (\Exceptio $e) {
            return response()->json(['msg' => 'Erro ao tentar salvar comentário.'], 500);
        }
    }

    public function getPosts()
    {
        $followingsIds = Auth::user()->followings->pluck('id')->toArray();

        $posts = Post::with([
            'user:id,name',
            'user.profile:id,picture,user_id',
            'comments:id,text,user_id,commentable_id,created_at',
            'comments.user:id,name',
            'comments.user.profile:id,picture,user_id',
            'comments.replies:id,text,user_id,commentable_id,created_at',
            'comments.replies.user:id,name',
            'comments.replies.user.profile:id,picture,user_id',
            'postable'
        ])->whereIn('user_id', $followingsIds)->orWhere('user_id', Auth::user()->id)->latest()->paginate(10);

        return response()->json($posts, 200);
    }

    public function comments($id)
    {
        $post = Post::findOrFail($id);
        $comments = $post->comments()->with([
            'user:id,name',
            'user.profile:id,picture,user_id',
            'replies:id,text,user_id,commentable_id,created_at',
            'replies.user:id,name',
            'replies.user.profile:id,picture,user_id'
        ])->get();

        return response()->json($comments, 200);
    }

    public function like($id)
    {
        $post = Post::findOrFail($id);

        $likes = Auth::user()->posts_liked()->where('likeable_id', $post->id)->first();

        if (!is_null($likes)) {
            $likes->pivot->delete();
        } else {
            Auth::user()->posts_liked()->save($post);

            $notification = new Notification();
            $notification->text = Auth::user()->name . ' curtiu o seu post.';
            $notification->save();

            $post->user->notifications()->save($notification);
            $notification->send($post->user);
        }

        return response()->json('Post curtido/descurtido com sucesso', 200);
    }

    public function share($id)
    {
        $post = Post::findOrFail($id);

        $newPost = new Post();
        $newPost->fill([
            'user_id' => Auth::user()->id,
            'shareable_type' => Post::class,
            'shareable_id' => $post->id,
            'text' => ''
        ]);

        Auth::user()->profile->posts()->save($newPost);

        return response()->json(['msg' => 'Post compartilhado na TL com sucesso.'], 201);
    }
}
