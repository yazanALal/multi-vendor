<?php

namespace App\Rules;

use App\Http\Traits\GeneralTrait;
use App\Http\Traits\ProductTrait;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

class CheckUserPermission implements Rule
{
    use ProductTrait;

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
        $user=$this->request->user('sanctum')->id;
        $orders=OrderItem::where('product_id',$this->productId($this->request->uuid))->pluck("order_id");
        if($orders){
            $exists = Order::whereIn('id', $orders)->where('user_id', $user)->exists();
            return $exists;
        }
        return false;
        
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'you are not allowed to review buy this product first';
    }
}
