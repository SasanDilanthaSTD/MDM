<?php

namespace App\Http\Controllers;

use App\Models\MasterBrand;
use App\Models\MasterCategory;
use App\Http\Custom\IsAuthenticated;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // check if user is authenticated
        IsAuthenticated::check();

        // create list of categories with pagination
        $categories = MasterCategory::with('brands')->paginate(5);


        return response()->json([
            'status' => true,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // check if user is authenticated
        IsAuthenticated::check();
        // validate request
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|integer|unique:master_categories,code',
        ]);

        // check if category already exists
        $category = MasterCategory::where('name', $data['name'])->first();
        if ($category) {
            return response()->json([
                'status' => false,
                'message' => 'Category already exists',
            ], 400);
        }
        // create category
        $category = MasterCategory::create($data);
        
        
        return response()->json([
            'status' => true,
            'message' => 'Category created successfully',
            'category' => $category,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterCategory $category)
    {
        // check if user is authenticated
        IsAuthenticated::check();

        return response()->json([
            'status' => true,
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterCategory $category)
    {
        // check if user is authenticated
        IsAuthenticated::check();
        
        // validate request
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|integer|unique:master_categories,code,' . $category->id,
        ]);

        // check if category already exists
        $existingCategory = MasterCategory::where('name', $data['name'])->first();
        if ($existingCategory && $existingCategory->id != $category->id) {
            return response()->json([
                'status' => false,
                'message' => 'Category already exists',
            ], 400);
        }
        // update category
        $category->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully',
            'category' => $category,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterCategory $category)
    {
        // check if user is authenticated
        IsAuthenticated::check();


        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully',
        ]);
    }
}
