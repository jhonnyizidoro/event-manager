<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Auth;

class UserProfileController extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function show(UserProfile $userProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function edit(UserProfile $userProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserProfile $userProfile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserProfile $userProfile)
    {
        //
    }

    public function posts($id)
    {
        $profile = UserProfile::findOrFail($id);
        $posts = $profile->posts()->with('user:id,name')->where('is_active', true)->orderBy('created_at', 'desc')->take(10)->get();

        return json($posts, 'Posts buscados.');
    }

    public function addPost(Request $request)
    {
        try {
            $profile = UserProfile::findOrFail(request('id'));

            $post = new Post();
            $post->fill($request->all());
            $post->user_id = Auth::user()->id;

            $profile->posts()->save($post);
            $post->user;

            return response()->json($post, 200);

        } catch (\Exception $e) {
            return response()->json(['msg' => 'Erro ao tentar salvar post.'.$e->getMessage()], 500);
        }
    }
}
