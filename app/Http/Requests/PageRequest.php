<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $method = $this->method();
        if ($method == 'PATCH') {
            return ['status' => 'required'];
        }

        $rule = [
            'page_name' => 'required|min:3|max:255',
            'page_seo_title' => 'min:3|max:300',
            'page_seo_description' => 'min:3|max:500',
            'page_description' => 'required',
        ];

        return $rule;
    }
}
