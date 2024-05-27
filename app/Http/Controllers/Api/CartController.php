<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Requests\UuidCartRequest;
use App\Http\Resources\CartResource;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\ProductTrait;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    use GeneralTrait;
    use ProductTrait;
     
    //retrieve user cart
    public function index(Request $request)
    {
        try {
            $products = Cart::where('user_id', $request->user('sanctum')->id)->with(['product', 'product.store'])->select('uuid', 'product_id', 'quantity')->get();
            return $this->apiResponse(CartResource::collection($products));
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }


    public function addToCart(AddToCartRequest $request)
    {
        try {
            $exists = Cart::where('user_id', $request->user('sanctum')->id)
                ->where("product_id", $this->productId($request->uuid))->exists();
            $quantity = Product::where('uuid', $request->uuid)->pluck('quantity')->first();
            if($quantity < $request->quantity){
                return $this->apiResponse(null, false, 'not enough quantity');
            }  
            if (!$exists ) {
                $userId = $request->user('sanctum')->id;
                $cart = [
                    'uuid' => Str::uuid(),
                    'quantity' => $request->quantity,
                    'price' => $this->productPrice($request->uuid) * $request->quantity,
                    'user_id' => $userId,
                    'product_id' => $this->productId($request->uuid),
                ];
                Cart::create($cart);
                return $this->apiResponse('Added successfully');
            }
            return $this->apiResponse(null, false, "already exists", 400);
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function update(UpdateCartRequest $request)
    {
        try {
            $cart = Cart::where('uuid', $request->cart)->first();
            $cart->quantity = $request->input('quantity');
            $cart->save();
            return $this->apiResponse('updated successfully');
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function delete(UuidCartRequest $request)
    {
        try {
            $delete = Cart::where('uuid', $request->cart)->delete();
            if ($delete) {
                return $this->apiResponse('deleted successfully');
            }
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }
}
