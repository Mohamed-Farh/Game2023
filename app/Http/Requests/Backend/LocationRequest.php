<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
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
                    'country_id'    => 'required',
                    'state_id'    => 'required',
                    'city_id'    => 'required',
                    'description'    => 'required',
                    'location'    => 'required',
                    'status'    => 'required'
                ];
            }

            case 'PUT':

            case 'PATCH':
            {
                return[
                    'country_id'    => 'required',
                    'state_id'    => 'required',
                    'city_id'    => 'required',
                    'description'    => 'required',
                    'location'    => 'required',
                    'status'    => 'required'
                ];
            }

            default: break;
        }

    }
}
