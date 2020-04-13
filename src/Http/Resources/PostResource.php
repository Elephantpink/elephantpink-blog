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
            "title" => $this->title,
            "subtitle" => $this->subtitle,
            "thumbnail_url" => $this->thumbnail_url,
            "header_image_url" => $this->header_image_url,
            "excerpt" => $this->excerpt,
            "body" => $this->body,
            "slug" => $this->slug,
            "author_id" => $this->author_id,
            "author" => $this->author->name,
            "publish_date" => $this->publish_date ? str_replace('-', '/', $this->publish_date) : null,
            "categories" => $this->categories->pluck("id"),
            "tags" => $this->tags->pluck("id"),
            "created_at" => str_replace('-', '/', $this->created_at),
            "updated_at" => $this->updated_at
        ];
    }
}
