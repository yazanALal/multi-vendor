<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UuidStoreRequest;
use App\Http\Resources\FollowingStoreResource;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\StoreTrait;
use App\Models\Follower;
use App\Models\Store;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    use GeneralTrait;
    use StoreTrait;

    public function follow(UuidStoreRequest $request)
    {
        try {
            $userId = $request->user("sanctum")->id;
            $storeId = $this->storeId($request->uuid);
            $follower = Follower::where('user_id', $userId)->where('store_id', $storeId)->exists();
            if (!$follower) {
                Follower::create([
                    "store_id" => $storeId,
                    "user_id" => $userId
                ]);
                Store::where('id', $storeId)->increment('followers');
                return $this->apiResponse('followed successfully');
            }
            return $this->apiResponse("already followed");
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function unFollow(UuidStoreRequest $request)
    {
        try {
            $userId = $request->user("sanctum")->id;
            $storeId = $this->storeId($request->uuid);
            $follower = Follower::where('user_id', $userId)->where('store_id', $storeId)->first();
            if ($follower) {
                $follower->delete();
                Store::where('id', $storeId)->decrement('followers');
                return $this->apiResponse('un followed successfully');
            }
            return $this->apiResponse(null, false, null, 400);
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function followingStores(Request $request)
    {
        try {
           $stores=Follower::where('user_id', $request->user("sanctum")->id)->with('store')->get();
            return $this->apiResponse(FollowingStoreResource::collection($stores));
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }

    }
}
