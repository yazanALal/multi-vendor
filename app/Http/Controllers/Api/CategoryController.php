<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Traits\GeneralTrait;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    use GeneralTrait;

    public function index()
    {
        try {
            $categories = Category::all();
            return $this->apiResponse(CategoryResource::collection($categories));
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }


    //for homepage
    public function RandomCategories()
    {
        try {
            $categories = Category::inRandomOrder()->take(8)->get();
            return $this->apiResponse(CategoryResource::collection($categories));
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

    

}
