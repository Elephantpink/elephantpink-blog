<?php

namespace EPink\Blog\Http\Controllers;

use EPink\Blog\Http\Requests\StoreTag;
use EPink\Blog\Models\Tag;
use EPink\Blog\Models\PostTag;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            
            return response()->json(['tags' => Tag::all()], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreTag  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTag $request)
    {
        $validated = $request->validated();

        try {

            $tag = Tag::create($validated);

            return response()->json(['tag' => $tag], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        try {
            return response()->json(['tag' => $tag], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreTag  $request
     * @param  Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTag $request, Tag $tag)
    {
        $validated = $request->validated();

        try {

            $tag->update($validated);

            return response()->json(['tag' => $tag]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        try {

            $tag_posts = PostTag::where('tag_id', $tag->id)->get();
            $tag_posts_ids = [];

            foreach ($tag_posts as $post_tag) {
                array_push($tag_posts_ids, $post_tag->post_id);
                $post_tag->delete();
            }

            $tag->delete();

            return response()->json(['tag_posts' => $tag_posts_ids], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }
}
