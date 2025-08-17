<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'new':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'price_low':
                    $query->orderBy('retails_price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('retails_price', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $limit = $request->get('limit', 20);
        $products = $query->paginate($limit)->appends($request->all());

        return response()->json([
            'status' => 200,
            'success' => true,
            'page' => $products->currentPage(),
            'limit' => $products->perPage(),
            'totalProducts' => $products->total(),
            'products' => $products->items()
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|unique:products,name',
            'category_id'    => 'nullable|exists:categories,id',
            'retails_price'  => 'nullable|numeric',
            'purchase_price' => 'nullable|numeric',
            'description'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ], 400);
        }

        $product = Product::create($validator->validated());

        return response()->json([
            'status'  => 201, 
            'success' => true,
            'message' => 'Product created successfully',
            'product' => $product
        ], 201); 

    }
}
