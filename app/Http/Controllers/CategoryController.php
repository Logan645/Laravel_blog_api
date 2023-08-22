<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CategoryStoreRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $catecories = Category::get();

        return CategoryResource::collection($catecories);
    }

    //取得特定分類
    public function show(Category $category)
    {
        $category -> find($category);

        return $category;
    }

    public function store(CategoryStoreRequest $request)
    {
        $data = $request->validated();//改成透過CategoryStoreRequest驗證

        $Category = Category::create($data);

        return new CategoryResource($Category);
    }

    public function update(Category $Category, Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);

        $Category -> update($data);
        return $Category;
        // return new CategoryResource($Category);
    }

    public function destroy(Category $Category, Request $request)
    {
        $Category -> delete();

        return new CategoryResource($Category);
    }
}
