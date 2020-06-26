<?php

namespace EPink\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    protected $table = "blog_translations";

    protected $fillable = [
        "post_id",
        "code",
        "language_code",
        "translation",
    ];

    public function post()
    {
        return $this->belongsTo("EPink\Blog\Models\Post","post_id");
    }
}
