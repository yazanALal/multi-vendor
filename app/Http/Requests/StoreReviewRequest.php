<?php

namespace App\Http\Requests;

use App\Http\Traits\GeneralTrait;
use App\Rules\ChecKNoReview;
use App\Rules\CheckUserPermission;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreReviewRequest extends FormRequest
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
            "uuid"=>[
                "required",
                "string",
                "exists:products,uuid",
                new CheckUserPermission($this),//check if user has permission to add review
                new ChecKNoReview($this),//check if user has review this product
            ],
            "comment"=>["string","max:255"],
            "rate"=>["required","integer", "between:1,5"]
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiResponse(null, false, $validator->errors(), 422));
    }
}
