<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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

        $rules = [
            'order' => 'required|integer',
            'name' => 'required|min:3|max:100',
            'description' => 'nullable|min:10|max:1000',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:5120',
        ];

        if ($method == 'POST') {
            $rules['image'] = 'required|mimes:jpg,jpeg,png,gif|max:5120';
        }

        return $rules;
    }
}
