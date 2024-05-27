<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStoreRequest;
use App\Http\Requests\EditStoreRequest;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\StoreUuidRequest;
use App\Http\Requests\UuidStoreRequest;
use App\Http\Resources\MyStoreResource;
use App\Http\Resources\StoreResource;
use App\Http\Resources\UserStoreResource;
use App\Http\Traits\GeneralTrait;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use illuminate\Support\Str;

class StoreController extends Controller
{
    use GeneralTrait;


    public function index(Request $request)
    {
        try {
            $stores = Store::select('uuid', 'name', 'image')->paginate(10);
            return $this->apiResponse(StoreResource::make($stores));
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function show(UuidStoreRequest $request)
    {
        try {
            $store = Store::where('uuid',$request->uuid)->with('products')->first();
            return $this->apiResponse(UserStoreResource::make($store));
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function createStore(CreateStoreRequest $request)
    {
        try {

            $user = $request->user('sanctum');
            $address = [
                'state' => $request->input('state'),
                'city' => $request->input('city'),
                'country' => $request->input('country'),
            ];

            $user->store()->create([
                'uuid' => Str::uuid(),
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'tagline' => $request->input('tagline'),
                'web_address' => $request->input('web_address'),
                'courier_name' => $request->input('courier_name'),
                'type' => $request->input('type'),
                'address' => $address,
            ]);
            $user->has_store = true;
            $user->save();
            return $this->apiResponse('your store created successfully');
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function myStore(Request $request)
    {
        try {
            $store = User::where('id', $request->user('sanctum')->id)->with(['store', 'store.products', 'store.followers'])->first();
            return $this->apiResponse(MyStoreResource::make($store));
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function storeImage(StoreImageRequest $request)
    {
        try {
            $store = $request->user('sanctum')->store;
            if ($request->hasFile('image')) {
                $image = $store->image;
                if ($image) {
                    Storage::delete($image);
                }
                $image = $request->file('image')->store('public/stores');
                $update = $store->update([
                    'image' => $image,
                ]);

                if ($update == 1) {
                    return $this->apiResponse('image stored successfully');
                }
            }
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function editStore(CreateStoreRequest $request)
    {
        try {

            $user = $request->user('sanctum');
            $address = [
                'state' => $request->input('state'),
                'city' => $request->input('city'),
                'country' => $request->input('country'),
            ];

            $user->store()->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'tagline' => $request->input('tagline'),
                'web_address' => $request->input('web_address'),
                'courier_name' => $request->input('courier_name'),
                'type' => $request->input('type'),
                'address' => $address,
            ]);
            return $this->apiResponse('your store updated successfully');
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function deleteStore(Request $request)
    {
        try {
            $user = $request->user('sanctum');
            $delete = $user->store->delete();
            if ($delete == 1) {
                $user->has_store = false;
                $user->save();
                return $this->apiResponse('your store has been deleted');
            }
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }
}
