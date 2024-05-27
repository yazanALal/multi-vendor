<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use GeneralTrait;

    public function logIn(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|string|email|',
                'password' => 'required|string|min:6',
            ]
        );
        if ($validator->fails()) {
            return $this->apiResponse(null, false, ($validator->errors()), 422);
        }
        try {
            $admin = Admin::whereEmail($request->email)->first();
            if (!$admin) {
                return $this->apiResponse(null, false, 'email or password is invalid', 401);
            }
            if (!Hash::check($request->password, $admin->password)) {
                return $this->apiResponse(null, false, 'email or password is invalid', 401);
            }

            $data["token"] = $admin->createToken('admin_token')->plainTextToken;
            return $this->apiResponse($data);
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        $admin = $request->user('admin');
        if ($admin) {
            $token = $admin->tokens();
            if ($token) {
                $token->delete();
            }
        }
        return $this->apiResponse(null, true, 'Logout success');
    }

}
