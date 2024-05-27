<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use GeneralTrait;

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|string|max:64',
                'last_name' => 'required|string|max:64',
                'phone' => 'required|string|unique:users|regex:/^0\d{9}$/',
                'email' => 'required|string|email|unique:users,email|max:64',
                'password' => 'required|string|min:6|max:64',
            ]
        );
        if ($validator->fails()) {
            return $this->apiResponse(null, false, $validator->errors(), 422);
        }
        try {

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return $this->apiResponse('register successfully');
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function logIn(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|string|max:64',
                'password' => 'required|string|min:6|max:64',
            ]
        );
        if ($validator->fails()) {
            return $this->apiResponse(null, false, $validator->errors(), 422);
        }
        try {
            $user = User::whereEmail($request->email)->first();
            if (!$user) {
                return $this->apiResponse(null, false, 'email or password is invalid', 401);
            }
            if (!Hash::check($request->password, $user->password)) {
                return $this->apiResponse(null, false, 'email or password is invalid', 401);
            }

            $data["token"] = $user->createToken('api_token')->plainTextToken;
            return $this->apiResponse($data);
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {

        if (auth('sanctum')->user()) {
            auth('sanctum')->user()->tokens()->delete();

            return $this->apiResponse('Logout success');
        }
    }
}
