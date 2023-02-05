<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class NineGameRequest extends FormRequest
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
                    'no_of_win_numbers' => ['required', 'integer'],
                    'win_numbers' => ['required'],
                    'timer' => ['required'],
                    'start' => ['required', 'after_or_equal:now'],
                    'end' => ['required', 'after:start'],
                    'image' => ['nullable', 'mimes:png,jpg,jpeg,svg|', 'max:5048'],

                    'price_name' => ['required'],
                    'price_value' => ['required', 'integer'],
                    'price_description' => ['required'],
                    'price_image' => ['nullable', 'mimes:png,jpg,jpeg,svg|', 'max:5048'],
                ];
            }

            case 'PUT':

            case 'PATCH':
            {
                return[
                    'no_of_win_numbers' => ['required', 'integer'],
                    'win_numbers' => ['required'],
                    'timer' => ['required'],
//                    'start' => ['nullable', 'after_or_equal:now'],
                    'end' => ['nullable', 'after:start'],

                    'price_name' => ['nullable'],
//                    'price_value' => ['nullable', 'integer'],
                    'price_description' => ['nullable'],
                ];
            }

            default: break;
        }

    }
}
