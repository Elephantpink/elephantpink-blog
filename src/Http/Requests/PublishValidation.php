<?php

namespace EPink\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublishValidation extends FormRequest
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
        return [
            'post' => 'required',
            'translations' => 'required',
            'categories' => 'nullable',
            'tags' => 'nullable',
            'thumbnail' => 'nullable',
            'header' => 'nullable'
        ];
    }
}
