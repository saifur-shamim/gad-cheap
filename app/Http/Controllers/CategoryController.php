<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Get all categories
     */
    public function index()
    {
        $categories = Category::all(['id', 'name', 'image']);

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    /**
     * Create a new category
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ], 400);
        }


        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }


        $category = Category::create([
            'name'  => $request->name,
            'image' => $imagePath ? '/storage/' . $imagePath : null,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Category created successfully',
            'category' => $category
        ], 201);
    }
}
