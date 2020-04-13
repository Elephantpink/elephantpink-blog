<?php

namespace EPink\Blog\Http\Controllers;

use EPink\Blog\Http\Controllers\Controller;
use EPink\Blog\Http\Requests\StorePost;
use EPink\Blog\Http\Resources\PostResource;
use EPink\Blog\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            return response()->json(['posts' => PostResource::collection(Post::all())], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePost  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        $validated = $request->validated();

        try {

            $post = Post::create($validated);

            return response()->json(['post' => new PostResource($post)], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        try {
            return response()->json(['post' => new PostResource($post)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StorePost  $request
     * @param  Post  $Post
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, Post $post)
    {
        $validated = $request->validated();

        try {

            $post->update($validated);

            return response()->json(['post' => new PostResource($post)], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Post  $Post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        try {
            
            $post->delete();

            return response()->json(null, 204);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }
}
