<?php

namespace EPink\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "blog_categories";

    protected $fillable = [
        "name",
        "description"
    ];

    public function posts()
    {
        return $this->hasManyThrough("EPink\Blog\Models\Post", "EPink\Blog\Models\PostCategory", "post_id", "id");
    }
}
