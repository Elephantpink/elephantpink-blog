<?php

namespace EPink\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthor extends FormRequest
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
        $authorId = $this->route('author') ? $this->route('author')->id : null;

        if($authorId) {
            return [
                "name" => "required|max:255",
                "email" => "required|max:255|unique:blog_authors,email,$authorId",
                "password" => "max:255|min:8",
                "additional_information" => "nullable",
                "is_admin" => "nullable",
                "is_disabled" => "nullable",
                "current_password" => "nullable"
            ];
        } else {
            return [
                "name" => "required|max:255",
                "email" => "required|max:255|unique:blog_authors,email",
                "password" => "required|max:255|min:8",
                "additional_information" => "nullable",
                "is_admin" => "nullable",
                "is_disabled" => "nullable",
                "current_password" => "nullable"
            ];
        }
    }
}
