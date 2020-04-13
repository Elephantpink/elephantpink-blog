<?php

namespace EPink\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $postId = $this->route('post') ? $this->route('post')->id : null;

        if($postId) {
            return [
                "title" => "required",
                "subtitle" => "nullable",
                "thumbnail_url" => "nullable",
                "header_image_url" => "nullable",
                "body" => "nullable",
                "author_id" => "required",
                "slug" => "required|unique:blog_posts,slug,$postId",
                "publish_date" => "nullable",
                "excerpt" => "nullable"
            ];
        } else {
            return [
                "title" => "required",
                "subtitle" => "nullable",
                "thumbnail_url" => "nullable",
                "header_image_url" => "nullable",
                "body" => "nullable",
                "author_id" => "required",
                "slug" => "required|unique:blog_posts,slug",
                "publish_date" => "nullable",
                "excerpt" => "nullable"
            ];
        }
    }
}
