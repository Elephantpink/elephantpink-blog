<?php

namespace EPink\Blog\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostTranslationResource extends JsonResource
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
            "post_id" => $this->post_id,
            "code" => $this->code,
            "language_code" => $this->language_code,
            "translation" => $this->translation,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
