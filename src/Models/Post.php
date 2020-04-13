<?php

namespace EPink\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = "blog_posts";

    protected $fillable = [
        "title",
        "subtitle",
        "excerpt",
        "body",
        "thumbnail_url",
        "header_image_url",
        "author_id",
        "slug",
        "publish_date"
    ];

    public function author()
    {
        return $this->belongsTo("EPink\Blog\Models\Author");
    }

    public function categories()
    {
        return $this->belongsToMany("EPink\Blog\Models\Category", "EPink\Blog\Models\PostCategory");
    }

    public function tags()
    {
        return $this->belongsToMany("EPink\Blog\Models\Tag", "EPink\Blog\Models\PostTag");
    }
}
