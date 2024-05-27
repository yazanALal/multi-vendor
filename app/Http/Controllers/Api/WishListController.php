<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UuidProductRequest;
use App\Http\Requests\UuidWishListRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\WishListResource;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\ProductTrait;
use App\Models\Product;
use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WishListController extends Controller
{
    use ProductTrait;
    use GeneralTrait;
    //retrieve user wishlist
    public function index(Request $request)
    {
        try {
            $userId = $request->user('sanctum')->id;
            $wishList = Product::whereIn('id', function ($query) use ($userId) {
                $query->select('product_id')
                    ->from('wish_lists')
                    ->where('user_id', $userId);
            })->with(['store' => function ($query) {
                $query->select('id', 'uuid');
            }, 'wishLists' => function ($query) {
                $query->select('uuid', 'product_id');
            }])->get();
            return $this->apiResponse(WishListResource::collection($wishList));
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function addToWishList(UuidProductRequest $request)
    {
        try {
            $productId = $this->productId($request->uuid);
            $userId = $request->user('sanctum')->id;
            $productExists = WishList::where("product_id", $productId)->where("user_id", $userId)->exists();
            if (!$productExists) {
                $data = [
                    "uuid" => Str::uuid(),
                    "product_id" => $productId,
                    "user_id" => $userId,
                ];
                WishList::create($data);
                return $this->apiResponse("added successfully");
            }
            return $this->apiResponse(null, false, 'already exists', 400);
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function delete(UuidWishListRequest $request)
    {
        try {
            $delete = WishList::where("uuid", $request->wishlist)->delete();
            if ($delete > 0) {
                return $this->apiResponse("deleted successfully");
            }
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }
}
