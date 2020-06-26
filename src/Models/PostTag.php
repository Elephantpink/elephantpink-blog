<?php

namespace EPink\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    protected $table = "blog_posts_tags";

    protected $fillable = [
        "post_id",
        "tag_id"
    ];

    public function post()
    {
        return $this->belongsTo("EPink\Blog\Models\Post");
    } 

    public function tag()
    {
        return $this->belongsTo("EPink\Blog\Models\Tag");
    }
}
