<?php

namespace EPink\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    protected $table = "blog_categories_translations";

    protected $fillable = [
        "category_id",
        "language_code",
        "translation",
    ];

    public function category()
    {
        return $this->belongsTo("EPink\Blog\Models\Category");
    }

}
