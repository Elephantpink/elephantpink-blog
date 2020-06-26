<?php

namespace EPink\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = "blog_posts";

    protected $fillable = [
        // "title",
        // "subtitle",
        // "excerpt",
        // "body",
        "thumbnail_url",
        "header_image_url",
        "author_id",
        // "slug",
        "publish_date"
    ];

    protected $appends = ['categories','tags'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo("EPink\Blog\Models\Author");
    }

    /**
     * @return mixed
     */
    public function getCategoriesAttribute()
    {
        return Category::
                    leftjoin('blog_posts_categories as PC','PC.category_id', '=', 'blog_categories.id')
                    ->where('PC.post_id',$this->id)
                    ->get();

    }

    /**
     * @return mixed
     */
    public function getTagsAttribute()
    {
        return Tag::
        leftjoin('blog_posts_tags as PT','PT.tag_id', '=', 'blog_tags.id')
            ->where('PT.post_id',$this->id)
            ->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        return $this->hasMany("EPink\Blog\Models\PostTranslation");
    }

}
