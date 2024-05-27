<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Http\Traits\GeneralTrait;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use GeneralTrait;

    public function index(Request $request){
        try {
            $notifications=$request->user('sanctum')->notifications;
            return $this->apiResponse(NotificationResource::collection($notifications));
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }
}
