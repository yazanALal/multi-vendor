<?php

namespace App\Http\Traits;

trait GeneralTrait
{

    public function apiResponse($data=null, bool $status = true, $message=null, $statusCode = 200)
    {
        $array=[
            'data' =>$data,
            'status' => $status ,
            'message' => $message,
            'statusCode' => $statusCode,
        ];
        return response($array,$statusCode);

    }

    public function unAuthorizeResponse()
    {
        return $this->apiResponse(null,0,'Unauthorize', 401);
    }

    public function notFoundResponse($more)
    {
        return $this->apiResponse(null,0, $more, 404);
    }

    public function successResponse($message)
    {
        return $this->apiResponse(null,true, $message, 200);
    }

    public function requiredField($message)
    {
        // return $this->apiResponse(null, false, $message, 200);
        return $this->apiResponse(null,false, $message, 400);
    }

}
