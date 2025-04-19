<?php

namespace App\Http\Controllers;

use App\Models\MasterBrand;
use Illuminate\Http\Request;
use App\Http\Custom\IsAuthenticated;
use GuzzleHttp\Promise\Is;

class BrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // check if user is authenticated
        IsAuthenticated::check();


        // create list of brands with pagination
        $brands = MasterBrand::paginate(5);

        return response()->json([
            'status' => true,
            'brands' => $brands,
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
            'code' => 'required|integer|unique:master_brands,code',
        ]);
        // check if brand already exists
        $brand = MasterBrand::where('name', $data['name'])->first();
        if ($brand) {
            return response()->json([
                'status' => false,
                'message' => 'Brand already exists',
            ], 400);
        }

        // create brand
        $brand = MasterBrand::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Brand created successfully',
            'brand' => $brand,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterBrand $brand)
    {
        // check if user is authenticated
        IsAuthenticated::check();

        return response()->json([
            'status' => true,
            'brand' => $brand,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterBrand $brand)
    {
        // check if user is authenticated
        IsAuthenticated::check();

        // validate request
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|integer|unique:master_brands,code,' . $brand->id,
        ]);

        // update brand
        $brand->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Brand updated successfully',
            'brand' => $brand,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterBrand $brand)
    {
        // check if user is authenticated
        IsAuthenticated::check();

        // delete brand
        $brand->delete();

        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully',
        ]);
    }
}
