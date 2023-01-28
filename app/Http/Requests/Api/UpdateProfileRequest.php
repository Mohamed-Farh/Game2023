<?php

namespace App\Http\Requests\Api;

use App\Traits\GeneralTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProfileRequest extends FormRequest
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
            'first_name'    => 'nullable|string|min:2',
            'last_name'     => 'nullable|string|min:2',
            'username'      => 'nullable|max:50|unique:users,username,'.\auth()->id(),
            'mobile'        => 'nullable|numeric|unique:users,mobile,'.\auth()->id(),
            'password'      => 'nullable|min:8',
            'user_image'    => 'nullable|mimes:png,jpg,jpeg,svg|max:5048',
            'address'       => 'nullable|string|min:8',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $this->validator->errors();
        $er = implode(' |+| ', $errors->all());
        throw new HttpResponseException(
            $this->responseValidationJsonFailed($er)
        );
    }
}

