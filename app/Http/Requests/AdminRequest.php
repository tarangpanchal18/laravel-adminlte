<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->method();

        if ($method == 'PATCH') {
            return ['status' => 'required'];
        }

        $rule = [
            'name' => 'required|min:3|max:100',
            'email' => ['required','min:3','max:100','email:rfc',Rule::unique('users', 'email')->ignore($this->user->id) ],
            'phone' => 'nullable|numeric|digits_between:9,12',
        ];

        if ($method == 'POST') {
            $rule += [
                'email' => ['required','min:3','max:100','email:rfc',Rule::unique('users', 'email')],
                'password' => 'required|min:3|max:20',
            ];
        }

        if(config('constants.feature_permission')) {
            $rule += ['role' => 'required' ];
        }

        return $rule;
    }
}
