<?php

namespace EPink\Blog\Http\Controllers;

use EPink\Blog\Models\PostTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use EPink\Blog\Http\Requests\{PublishValidation, StorePost, StorePostTranslation};
use EPink\Blog\Http\Resources\PostResource;
use EPink\Blog\Models\Post;
use EPink\Blog\Models\PostCategory;
use EPink\Blog\Models\PostTag;
use Illuminate\Support\Facades\Storage;

class PublishController extends Controller
{

    /** @var array  */
    // Note : set it on validateData
    private $input_data = [];

    /** @var null  */
    // Note : set it on validateData
    private $post_id = null;

    /**
     * Creates a post and relates it to categories and tags
     *
     * @param  PublishValidation  $request
     * @return \Illuminate\Http\Response
     */
    public function publishPost(PublishValidation $request)
    {

        try {

            return response()->json(['post' => $this->savePost($request)], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error.'], 500);
        }
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function editPost(PublishValidation $request)
    {
        try {

            return response()->json(['post' => $this->savePost($request)], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error.'], 500);
        }
    }

    /**
     * @param Request $request
     * @return PostResource
     * @throws \Exception
     */
    private function savePost(PublishValidation $request)
    {
        try {

            $this->validateData($request);

            $data = [
                // 'slug'          =>  $this->input_data['post']['slug'],
                'author_id'     =>  $this->input_data['post']['author_id'],
                'publish_date'  =>  !empty($this->input_data['post']['publish_date']) ? $this->input_data['post']['publish_date']  : null
            ];

            if(!empty($this->post_id))
            {

                $post = Post::find($this->post_id);

                $post->update($data);

            }else{

                $post = Post::create($data);
            }

            $this->updateTranslations($post, $this->input_data['translations']);

            $this->updatePostCategories($post, $this->input_data['categories']);

            $this->updatePostTags($post, $this->input_data['tags']);

            $post->refresh();

            $this->storeImages($post , $this->input_data['post'], $post->translations()->select('translation')->where('code', 'slug')->get()[0]->translation);

            DB::commit();

            return new PostResource($post);

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Error saving post. '.$e->getMessage());
        }
    }


    /**
     * @param $request
     */
    private function validateData($request)
    {
        //global validation
        $validated = $request->validated();

        $this->post_id =  !empty($request->route('post')) ? $request->route('post') : null;

        //specific validations
        $this->input_data['post']           = Validator::make($validated["post"], (new StorePost())->rules($this->post_id))->validate();

        $translations_rules = (new StorePostTranslation())->rules();

        foreach ($validated["translations"] as $translation)
        {
            $this->input_data['translations'][]   = Validator::make($translation, $translations_rules)->validate();
        }

        $this->input_data['categories']     = !empty($validated["categories"])  ? $validated["categories"]  : null;
        $this->input_data['tags']           = !empty($validated["tags"])        ? $validated["tags"]        : null;
        $this->input_data['post']['new_thumbnail']  = !empty($validated['thumbnail'])   ? $validated['thumbnail']                 : null;
        $this->input_data['post']['new_header']     = !empty($validated['header'])      ? $validated['header']                 : null;

    }

    /**
     * @param Post $post
     * @throws \Exception
     */
    private function storeImages(Post $post , $post_validated, $slug)
    {
        try{
            // $filename = substr($post_validated['slug'], 0, 20);
            $filename = substr($slug, 0, 20);

            //remove prev images on update
            if(!empty($post->thumbnail_url) && file_exists(storage_path("/app/public/") ."/".$post->thumbnail_url))
            {
                Storage::delete(storage_path("/app/public/") ."/".$post->thumbnail_url);
            }

            if(!empty($post->header_image_url) && file_exists(storage_path("/app/public/") ."/".$post->header_image_url))
            {
                Storage::delete(storage_path("/app/public/") ."/".$post->thumbnail_url);
            }

            if($post_validated['new_thumbnail'] && $post_validated['new_thumbnail'] != 'null') {
                $thumbnail_url = $this->storeBase64($post_validated['new_thumbnail'], $filename);

                $post->update(['thumbnail_url' => $thumbnail_url]);
            }

            if($post_validated['new_header'] && $post_validated['new_header'] != 'null') {
                $header_image_url = $this->storeBase64($post_validated['new_header'], $filename);

                $post->update(['header_image_url' => $header_image_url]);
            }

            // $thumbnail_url= $post_validated['thumbnail_url'] != 'null' ?
            //     $this->storeBase64($post_validated['thumbnail_url'], $filename) :
            //     null;

            // $header_image_url = $post_validated['header_image_url'] != 'null' ?
            //     $this->storeBase64($post_validated['header_image_url'], $filename) :
            //     null;

            // $post->update(['thumbnail_url' => $thumbnail_url,'header_image_url' => $header_image_url]);

        }catch (\Throwable $exception)
        {
            throw new \Exception('Error saving post images. '.$exception->getMessage());
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

        try{

            PostCategory::where('post_id', $post->id)->delete();

            if(!empty($categories))
            {
                //add new categories
                foreach ($categories as $category) {

                    PostCategory::create([ 'post_id' => $post->id,'category_id' => $category]);
                }
            }

        }catch (\Throwable $exception)
        {
            throw new \Exception('Error saving post categories. '.$exception->getMessage());
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

        try{

            PostTag::where('post_id', $post->id)->delete();

            if(!empty($tags))
            {
                foreach ($tags as $tag) {

                    PostTag::create(['post_id' => $post->id,'tag_id' => $tag]);
                }
            }

        }catch (\Throwable $exception)
        {
            throw new \Exception('Error saving post tags. '.$exception->getMessage());
        }

    }

    /**
     *
     * Updates translations related to a post
     *
     * @param Post $post
     * @param $translations
     */
    private function updateTranslations(Post $post, $translations)
    {

        try{

            PostTranslation::where('post_id', $post->id)->delete();

            foreach ($translations as $translation)
            {

                $translation['post_id'] = $post->id;

                PostTranslation::create($translation);
            }

        }catch (\Throwable $error)
        {
            throw new \Exception('Error saving post translations. '.$error->getMessage());
        }

    }


    /**
     * @param $image
     * @param string $name
     * @return string
     */
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
     * 
     * DEPRECATED
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
