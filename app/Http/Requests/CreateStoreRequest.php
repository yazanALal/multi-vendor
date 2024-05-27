<?php

namespace App\Http\Requests;

use App\Http\Traits\GeneralTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateStoreRequest extends FormRequest
{
    use GeneralTrait;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'=>['required', 'string','max:64'],
            'web_address'=>['required', 'string','max:64'],
            'description'=>['required', 'string','max:255'],
            'type'=>['required', 'string','max:64'],
            'country'=>['required', 'string','max:64'],
            'state'=>['required', 'string','max:64'],
            'city'=>['required', 'string','max:64'],
            'courier_name' => ['required', 'string', 'max:64'],
            'tagline' => ['required', 'string', 'max:64']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiResponse(null, false, $validator->errors(), 422));
    }
}
