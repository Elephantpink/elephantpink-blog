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
        return $this->hasManyThrough(Post::class,
                                    PostCategory::class,
                                    "post_id", "id");
    }
}
