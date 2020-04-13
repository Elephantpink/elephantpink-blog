<?php

namespace EPink\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTag extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $tagId = $this->route('tag') ? $this->route('tag')->id : null;

        if($tagId) {
            return [
                "name" => "required|max:255|unique:blog_tags,name,$tagId",
                "description" => "nullable"
            ];
        } else {
            
            return [
                "name" => "required|unique:blog_tags,name|max:255",
                "description" => "nullable"
            ];
        }
    }
}
