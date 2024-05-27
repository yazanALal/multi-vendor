<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use GeneralTrait;

    public function store(StoreCategoryRequest $request){
        try {
            $image = $request->file('image')->store('public/category');
            Category::create([
                'uuid'=>Str::uuid(),
                'name' => $request->input("name"),
                'image' => $image,
            ]);
            return $this->apiResponse('Category Created Successfully');
        } catch (\Exception $e) {
            return $this->apiResponse(null, false, $e->getMessage(), 500);
        }
    }

}
