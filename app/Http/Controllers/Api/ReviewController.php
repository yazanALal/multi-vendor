<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UuidProductRequest;
use App\Http\Resources\ReviewResource;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\ProductTrait;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ProductTrait;
    use GeneralTrait;

    //retrieve all reviews for a product
    public function index(UuidProductRequest $request)
    {
        try {
            $reviews = Review::where('product_id', $this->productId($request->uuid))
            ->with(['user'=>function($query){
                $query->select('id','last_name', 'first_name');
            }])->get();
            
            return $this->apiResponse(ReviewResource::collection($reviews));
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function store(StoreReviewRequest $request)
    {
        try {
            $productId= $this->productId($request->uuid);
            $data=[
                'rate'=>$request->input('rate'),
                'comment'=>$request->input('comment'),
                'user_id'=>$request->user('sanctum')->id,
                'product_id'=>$this->productId($request->uuid)
            ];
            Review::create($data);
            $avg_rate=Review::where('product_id',$productId)->avg('rate');
            Product::where('id', $productId)->update(['rate'=>$avg_rate]);
            return $this->apiResponse("Review Added Successfully");
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    
}
