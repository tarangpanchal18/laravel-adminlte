<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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

        return [
            'name' => 'required|min:3|max:100',
            'parent_id' => 'nullable|integer',
            'description' => 'nullable|min:3|max:100',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:5120',
        ];
    }
}
