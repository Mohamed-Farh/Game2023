<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class SocialMediaRequest extends FormRequest
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
                    'type'    => 'required',
                    'status'    => 'required',
                    'link'     => 'required|url'
                ];
            }

            case 'PUT':

            case 'PATCH':
            {
                return[
                    'type'    => 'required',
                    'status'    => 'required',
                    'link'     => 'required|url'
                ];
            }

            default: break;
        }

    }
}
