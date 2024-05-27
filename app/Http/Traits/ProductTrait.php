<?php

namespace App\Http\Traits;

use App\Models\Product;

trait ProductTrait
{
    public function productId($uuid)
    {
        return Product::where('uuid', $uuid)->first('id')->id;
    }


    public function productPrice($uuid)
    {
        return Product::where('uuid', $uuid)->first('price')->price;
    }
}
