<?php

namespace App\Http\Requests;

use App\Http\Traits\GeneralTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
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
            'uuid' => ['required', 'string', 'exists:products,uuid'],
            'name' => ['required', 'string', 'max:64'],
            'description' => ['required', 'string', 'max:255'],
            'images' => ['required', 'array', 'max:4', 'min:1'],
            'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'category' => ['required', 'string', 'max:64'],
            'price' => ['required', 'numeric', 'regex:/^[0-9]+(\.[0-9]+)?$/'],
            'location' => ['required', 'string', 'max:64'],
            'condition' => ['required', 'string', 'max:64'],
            'price_type' => ['required', 'string', 'max:64'],
            'delivery_details' => ['required', 'string', 'max:64'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiResponse(null, false, $validator->errors(), 422));
    }
}
