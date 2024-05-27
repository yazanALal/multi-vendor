<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\UuidProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Traits\GeneralTrait;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use GeneralTrait;

    public function searchProduct(SearchProductRequest $request)
    {
        try {
            $query = Product::query();
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('category', 'like', '%' . $request->search . '%');
            $products = $query->with(['store' => function ($query) {
                $query->select('id','uuid', 'name');
            }])->get();
            return $this->apiResponse(ProductResource::collection($products));
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function storeProduct(StoreProductRequest $request)
    {
        try {
            $user = $request->user('sanctum');
            $images = $request->file('images');
            $imagePaths = [];

            foreach ($images as $image) {
                $imagePath = $image->store('public/products');
                $imagePaths[] = $imagePath;
            }

            $user->store->products()->create([
                'uuid' => Str::uuid(),
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'category' => $request->input('category'),
                'location' => $request->input('location'),
                'price' => $request->input('price'),
                'price_type' => $request->input('price_type'),
                'condition' => $request->input('condition'),
                'delivery_details' => $request->input('delivery_details'),
                'images' => $imagePaths,
                'quantity' => $request->input('quantity'),
                'rate' => 0,
            ]);
            return $this->apiResponse("product created successfully");
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function updateProduct(UpdateProductRequest $request)
    {
        try {
            $product = Product::where('uuid', $request->input('uuid'))->first();
            foreach ($product->images as $image) {
                Storage::delete($image);
            }

            $images = $request->file('images');
            $imagePaths = [];

            foreach ($images as $image) {
                $imagePath = $image->store('public/products');
                $imagePaths[] = $imagePath;
            }

            $product->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'category' => $request->input('category'),
                'location' => $request->input('location'),
                'price' => $request->input('price'),
                'price_type' => $request->input('price_type'),
                'condition' => $request->input('condition'),
                'delivery_details' => $request->input('delivery_details'),
                'images' => $imagePaths,
                'quantity' => $request->input('quantity'),
            ]);
            return $this->apiResponse("product updated successfully");
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function softDelete(UuidProductRequest $request)
    {
        try {
            $delete = Product::where('uuid', $request->input('uuid'))->delete();
            if ($delete > 0) {
                return $this->apiResponse('product deleted successfully');
            }
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function forceDelete(UuidProductRequest $request)
    {
        try {
            $delete = Product::withTrashed()->where('uuid', $request->input('uuid'))->forceDelete();
            if ($delete > 0) {
                return $this->apiResponse('product deleted successfully');
            }
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function trash(Request $request)
    {
        try {
            $trash = Product::onlyTrashed()->where("store_id", $request->user('sanctum')->store->id)->get();
            return $this->apiResponse(ProductResource::collection($trash));
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function restore(UuidProductRequest $request)
    {
        try {
            $restore = Product::onlyTrashed()->where('uuid', $request->input('uuid'))->restore();
            if ($restore > 0) {
                return $this->apiResponse('product restored successfully');
            }
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    public function emptyTrash(Request $request)
    {
        try {
            $delete = Product::onlyTrashed()->where('store_id', $request->user('sanctum')->store->id)->forceDelete();
            if ($delete > 0) {
                return $this->apiResponse('products deleted successfully');
            }
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }
}
