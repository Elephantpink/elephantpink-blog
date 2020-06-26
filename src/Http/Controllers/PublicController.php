<?php

namespace EPink\Blog\Http\Controllers;

use EPink\Blog\Http\Controllers\Controller;
use EPink\Blog\Models\Author;
use EPink\Blog\Models\Category;
use EPink\Blog\Models\Post;
use EPink\Blog\Models\Tag;
use EPink\Blog\Http\Resources\CategoryResource;
use EPink\Blog\Http\Resources\PostResource;
use EPink\Blog\Http\Resources\TagResource;
use Carbon\Carbon;

class PublicController extends Controller
{
  public function getData()
  {
    try {

      return response()->json([
        'categories' => CategoryResource::collection(Category::all()),
        'posts' => PostResource::collection(Post::where('publish_date', '<', Carbon::now())->orWhereNull('publish_date')->get()),
        'tags' => TagResource::collection(Tag::all())
      ]);

    } catch (\Exception $e) {
      return response()->json(['message' => 'Unexpected server error'], 500);
    }
  }
}