<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
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
        switch ($this->method()){
            case 'POST':
            {
                return[
                    'name' => ['nullable', 'string'],
                    'win_tokens' => ['required'],
                    'cost' => ['nullable'],
                    'start' => ['required', 'after_or_equal:now'],
                    'end' => ['required', 'after:start'],
                    'image' => ['nullable', 'mimes:png,jpg,jpeg,svg|', 'max:5048'],
                ];
            }

            case 'PUT':

            case 'PATCH':
            {
                return[
                    'name' => ['nullable', 'string'],
                    'win_tokens' => ['required'],
                    'cost' => ['nullable'],
//                    'start' => ['required', 'after_or_equal:now'],
                    'end' => ['required', 'after:start'],
                    'image' => ['nullable', 'mimes:png,jpg,jpeg,svg|', 'max:5048'],
                ];
            }

            default: break;
        }

    }
}
