<?php

namespace App\Http\Traits;

use App\Models\Store;

trait StoreTrait
{

    public function storeId($uuid)
    {
        return Store::where('uuid', $uuid)->first('id')->id;
    }
}
