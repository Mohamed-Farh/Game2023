<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class WorkingTimeRequest extends FormRequest
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
                    'day'       => 'required',
                    'start'     => 'required',
                    'end'       => 'required|after:start',
                    'status'    => 'required'
                ];
            }

            case 'PUT':

            case 'PATCH':
            {
                return[
                    'day'       => 'required',
                    'start'     => 'required',
                    'end'       => 'required|after:start',
                    'status'    => 'required'
                ];
            }

            default: break;
        }

    }
}
