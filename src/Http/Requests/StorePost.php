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
    public function rules($post_id = null)
    {
        // $slug_rule = !empty($post_id) ? "required|unique:blog_posts,slug,$post_id" : "required|unique:blog_posts,slug";

        return [
            //"title" => "required",
            //"subtitle" => "nullable",
            //"body" => "nullable",
            "thumbnail_url"     => "nullable",
            "header_image_url"  => "nullable",
            "author_id"         => "required",
            // "slug"              => $slug_rule,
            "publish_date"      => "nullable",
            // "excerpt"           => "nullable",
        ];
    }
}
