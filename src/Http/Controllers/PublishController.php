<?php

namespace EPink\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use EPink\Blog\Http\Requests\StorePost;
use EPink\Blog\Http\Resources\PostResource;
use EPink\Blog\Models\Author;
use EPink\Blog\Models\Category;
use EPink\Blog\Models\Post;
use EPink\Blog\Models\PostCategory;
use EPink\Blog\Models\PostTag;
use EPink\Blog\Models\Tag;

class PublishController extends Controller
{
    /**
     * Creates a post and relates it to categories and tags
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function publishPost(Request $request)
    {
        $validated = $request->validate([
            'post' => 'required',
            'categories' => 'nullable',
            'tags' => 'nullable'
        ]);

        try {
    
            $post_request = new StorePost();
    
            $post_validated = Validator::make($validated["post"], $post_request->rules())->validate();
            $new_post = Post::create($post_validated);
    
            $this->updatePostCategories($new_post, $validated["categories"]);
            $this->updatePostTags($new_post, $validated["tags"]);

            $new_post->refresh();
    
            return response()->json([
                'post' => new PostResource($new_post)
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }

    public function editPost(Request $request, Post $post)
    {
        $validated = $request->validate([
            'post' => 'required',
            'categories' => 'nullable',
            'tags' => 'nullable',
            'thumbnail' => 'nullable',
            'header' => 'nullable'
        ]);

        try {
    
            // $post_request = new StorePost();
            $rules = [
                "title" => "required",
                "subtitle" => "nullable",
                "thumbnail_url" => "nullable",
                "header_image_url" => "nullable",
                "body" => "nullable",
                "author_id" => "required",
                "slug" => "required|unique:blog_posts,slug,$post->id",
                "publish_date" => "nullable",
                "excerpt" => "nullable"
            ];

            $post_validated = Validator::make(json_decode($validated["post"], true), $rules);
            
            if(!$post_validated->fails()) {
                $post_validated = json_decode($validated["post"], true);
                $post = Post::find($post_validated["id"]);

                $post->update($post_validated);

                if ($validated['header'] !== "null" && $validated['thumbnail'] !== "null" ) {
                    $post->update([
                        'thumbnail_url' => $this->storeBase64($validated['thumbnail'], substr($post->slug, 0, 20)),
                        'header_image_url' => $this->storeFile($request, 'header', substr($post->slug, 0, 20))
                    ]);
                } else if ($validated['thumbnail'] !== "null") {
                    $post->update([
                        'thumbnail_url' => $this->storeBase64($validated['thumbnail'], substr($post->slug, 0, 20))
                    ]);
                }

                $this->updatePostCategories($post, $post_validated["categories"]);
                $this->updatePostTags($post, $post_validated["tags"]);

                $post->refresh();
        
                return response()->json([
                    'post' => new PostResource($post)
                ], 200);

            } else {
                return response()->json(['errors' => $post_validated->errors()], 400);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }

    public function deletePost(Post $post)
    {
        try {
            
            PostCategory::where('post_id', $post->id)->delete();
            PostTag::where('post_id', $post->id)->delete();

            $post->delete();
            
            return response()->json(null, 204);
            
        } catch(\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }

    /** 
     * Finds a post by id and relate it to an array of categories
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editPostCategories(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required',
            'categories' => 'required'
        ]);

        try {
    
            $post = Post::find($validated["post_id"]);
    
            if ($post) {
    
                $this->updatePostCategories($post, $validated["categories"]);
    
                return response()->json(null, 204);
    
            } else {
                return response()->json(['message' => 'Post not found'], 404);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }

    /**
     * Finds a post by id and relate it to an array of tags
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editPostTags(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required',
            'tags' => 'required'
        ]);

        try {
    
            $post = Post::find($validated["post_id"]);
    
            if ($post) {
    
                $this->updatePostTags($post, $validated["tags"]);
    
                return response()->json(null, 204);
                
            } else {
                return response()->json(['message' => 'Post not found'], 404);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }

    /**
     * Updates the categories related to the post
     * 
     * @param Post $post
     * @param array $categories
     */
    private function updatePostCategories (Post $post, $categories)
    {
        $post_categories_removed = $post->categories->whereNotIn('id', $categories);

        foreach ($post_categories_removed as $category) {
            PostCategory::where('post_id', $post->id)
                ->where('category_id', $category->id)
                ->delete();
        }

        foreach ($categories as $category) {
            if (!$post->categories->find($category) && Category::find($category)) {
                PostCategory::create([
                    'post_id' => $post->id,
                    'category_id' => $category,
                ]);
            }
        }
    }

    /**
     * Updates the tags related to the post
     * 
     * @param Post $post
     * @param array $tags
     */
    private function updatePostTags (Post $post, $tags)
    {
        $post_tags_removed = $post->tags->whereNotIn('id', $tags);

        foreach ($post_tags_removed as $tag) {
            PostTag::where('post_id', $post->id)
                ->where('tag_id', $tag->id)
                ->delete();
        }

        foreach ($tags as $tag) {
            if (!$post->tags->find($tag) && Tag::find($tag)) {
                PostTag::create([
                    'post_id' => $post->id,
                    'tag_id' => $tag,
                ]);
            }
        }
    }

    public function storeBase64($image, $name = '') 
    {
        $name = $name . '-thumb-' . rand(100000, 1000000);
        $img = preg_replace('/^data:image\/\w+;base64,/', '', $image);
        $img = str_replace(' ', '+', $img);
        $extension = explode(';', $image)[0];
        $extension = explode('/', $extension)[1]; // png or jpg etc
        $imageName = $name . "." . $extension;

        \File::put(storage_path(). "/app/public/" . $imageName, base64_decode($img));

        return "/storage/" . $imageName;
    }

    /**
     * @param Request $request
     * @param $fieldName
     * @return null|string
     */
    public function storeFile($request, $fieldName, $filename = null)
    {
        $imageName = null;

        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);

            if(!$filename) $filename = preg_replace("/\s+/", "-", $file->getClientOriginalName());
            $filename .= '-' . rand(100000, 1000000);

            $imageName = $filename . '.' . $file->getClientOriginalExtension();
            $request->file($fieldName)->move(storage_path("/app/public/"), $imageName);

            // \File::put(storage_path() . "/app/public/" . $imageName, $request->file($fieldName));
        }

        return "/storage/" . $imageName;
    }
}
