<?php

namespace EPink\Blog\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "thumbnail_url" => $this->thumbnail_url,
            "header_image_url" => $this->header_image_url,
            "author_id" => $this->author_id,
            "author" => $this->author->name,
            "publish_date" => $this->publish_date ? str_replace('-', '/', $this->publish_date) : null,
            "categories" => !empty($this->categories) ? $this->categories->pluck('category_id') : null,
            "tags" => !empty($this->tags) ? $this->tags->pluck("tag_id") : null,
            "created_at" => str_replace('-', '/', $this->created_at),
            "updated_at" => $this->updated_at,
            "translations" => !empty($this->translations) ? $this->translations()->select('code', 'language_code', 'translation')->get() : null,
        ];
    }
}
