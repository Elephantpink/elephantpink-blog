<?php

namespace EPink\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = "blog_tags";

    protected $fillable = [
        "name",
        "description"  
    ];

    public function posts()
    {
        return $this->hasManyThrough("EPink\Blog\Models\Post", "EPink\Blog\Models\PostTag", "tag_id", "id");
    }
}
