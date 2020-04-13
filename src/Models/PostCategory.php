<?php

namespace EPink\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $table = "blog_posts_categories";

    protected $fillable = [
        "post_id",
        "category_id"
    ];

    public function post()
    {
        return $this->belongsTo("EPink\Blog\Models\Post", "post_id");
    }

    public function category()
    {
        return $this->belongsTo("EPink\Blog\Models\Category", "category_id");
    }
}
