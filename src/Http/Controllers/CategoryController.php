<?php

namespace EPink\Blog\Http\Controllers;

use EPink\Blog\Http\Controllers\Controller;
use EPink\Blog\Http\Requests\StoreCategory;
use EPink\Blog\Models\Category;
use EPink\Blog\Models\PostCategory;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            
            return response()->json(['categories' => Category::all()], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCategory  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategory $request)
    {
        $validated = $request->validated();

        try {

            $validated = $request->validated();

            $category = Category::create($validated);

            return response()->json(['category' => $category], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        try {
            return response()->json(['category' => $category], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreCategory  $request
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategory $request, Category $category)
    {
        $validated = $request->validated();

        try {

            $category->update($validated);

            return response()->json(['category' => $category], 200);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {

            $category_posts = PostCategory::where('category_id', $category->id)->get();
            $category_posts_ids = [];

            foreach ($category_posts as $post_category) {
                array_push($category_posts_ids, $post_category->post_id);
                $post_category->delete();
            }

            $category->delete();

            return response()->json(['category_posts' => $category_posts_ids], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }
}
