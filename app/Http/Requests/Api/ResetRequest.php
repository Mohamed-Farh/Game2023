<?php

namespace App\Http\Requests\Api;

use App\Traits\GeneralTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ResetRequest extends FormRequest
{
    use GeneralTrait;
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
            'token'=>'nullable',
            'email' => 'required_without:mobile|email|exists:users,email',
            'mobile' => 'required_without:email|exists:users,mobile',
            "password" => "required|confirmed|min:6",
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator,$this->returnValidationErrorNew($validator)) ;
    }
}
