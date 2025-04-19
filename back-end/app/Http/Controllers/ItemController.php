<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterItem;
use App\Http\Custom\IsAuthenticated;
class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get user id
        $user_id = auth()->user()->id;
        // check if user is authenticated
        if (!$user_id) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }
        
        // ger list of items with pagination
        $items = MasterItem::where('user_id', $user_id)->with('category', 'brand')->paginate(5);

        return response()->json([
            'status' => true,
            'items' => $items,
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
            'code' => 'required|integer|unique:master_items,code',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // check if item already exists
        $item = MasterItem::where('name', $data['name'])->first();
        if ($item) {
            return response()->json([
                'status' => false,
                'message' => 'Item already exists',
            ], 400);
        }

        // store file
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('attachments', $filename);
            $data['attachment'] = $filename;
        }

        // get user id
        $user_id = auth()->user()->id;

        // create item
        $item = MasterItem::create([
            'name' => $data['name'],
            'code' => $data['code'],
            'attachment' => $data['attachment'] ?? null,
            'user_id' => $user_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Item created successfully',
            'item' => $item,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterItem $item)
    {
        // check if user is authenticated
        IsAuthenticated::check();

        return response()->json([
            'status' => true,
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterItem $item)
    {
        // check if user is authenticated
        IsAuthenticated::check();

        // validate request
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|integer|unique:master_items,code,' . $item->id,
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // check if item already exists
        $existingItem = MasterItem::where('name', $data['name'])->first();
        if ($existingItem && $existingItem->id != $item->id) {
            return response()->json([
                'status' => false,
                'message' => 'Item already exists',
            ], 400);
        }

        // store file
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('attachments', $filename);
            $data['attachment'] = $filename;
        }

        // update item
        $item->update($data);
        return response()->json([
            'status' => true,
            'message' => 'Item updated successfully',
            'item' => $item,
        ]); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterItem $item)
    {
        // check if user is authenticated
        IsAuthenticated::check();

        // check if user has the required role
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            return response()->json([
                'status' => false,
                'message' => 'Forbidden: You do not have the required permissions',
            ], 403);
        }

        // delete item
        $item->delete();
        return response()->json([
            'status' => true,
            'message' => 'Item deleted successfully',
        ]);
    }
}
