<?php

namespace App\Rules;

use App\Http\Traits\ProductTrait;
use App\Models\Review;
use Illuminate\Contracts\Validation\Rule;

class ChecKNoReview implements Rule
{
    use ProductTrait;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = $this->request->user('sanctum')->id;
        $exists = Review::where('user_id',$user)->where('product_id',$this->productId($this->request->uuid))->exists();
        return !$exists;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'you already reviewed this product';
    }
}
