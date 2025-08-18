<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    /**
     * GET /api/search?q=term&type=all|products|brands|categories&limit=10
     */
    public function search(Request $request)
    {
        // 1) Validate input
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:1',
            'type'  => 'nullable|in:all,products,brands,categories',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);




        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'success' => false,
                'errors'  => $validator->errors(),
            ], 400);
        }

        $term  = $request->input('query');
        $type  = $request->input('type', 'all');
        $limit = (int) $request->input('limit', 10);

        $results = [
            'products'   => [],
            'brands'     => [],
            'categories' => [],
        ];

        // 2) Products search (by product name/barcode, brand name, category name)
        if ($type === 'all' || $type === 'products') {
            $products = Product::with([
                'category:id,name,image',
                'brand:id,name,image'
            ])
                ->where(function ($q) use ($term) {
                    $like = '%' . $term . '%';
                    $q->where('name', 'like', $like)
                        ->orWhere('barcode', 'like', $like)
                        ->orWhereHas('category', function ($qc) use ($like) {
                            $qc->where('name', 'like', $like);
                        })
                        ->orWhereHas('brand', function ($qb) use ($like) {
                            $qb->where('name', 'like', $like);
                        });
                })
                ->limit($limit)
                ->get([
                    'id',
                    'name',
                    'barcode',
                    'retails_price',
                    'image_path',
                    'category_id',
                    'brand_id'
                ]);

            // Optionally add full image URL for product image_path
            $products->transform(function ($p) {
                $p->image_url = $p->image_path ? url($p->image_path) : null;
                return $p;
            });

            $results['products'] = $products;
        }

        // 3) Brands search
        if ($type === 'all' || $type === 'brands') {
            $brands = Brand::where('status', true)
                ->where('name', 'like', '%' . $term . '%')
                ->limit($limit)
                ->get(['id', 'name', 'image']);

            // Optional: add full image URL
            $brands->transform(function ($b) {
                $b->image_url = $b->image ? url($b->image) : null;
                return $b;
            });

            $results['brands'] = $brands;
        }

        // 4) Categories search
        if ($type === 'all' || $type === 'categories') {
            $categories = Category::where('name', 'like', '%' . $term . '%')
                ->limit($limit)
                ->get(['id', 'name', 'image']);

            // Optional: add full image URL
            $categories->transform(function ($c) {
                $c->image_url = $c->image ? url($c->image) : null;
                return $c;
            });

            $results['categories'] = $categories;
        }

        // 5) Response
        // Count results (arrays or collections are countable)
        return response()->json([
            'status'  => 200,
            'success' => true,
            'message' => 'Search results fetched successfully',
            'query'   => $term,
            'type'    => $type,
            'counts'  => [
                'products'   => isset($results['products']) ? count($results['products']) : 0,
                'brands'     => isset($results['brands']) ? count($results['brands']) : 0,
                'categories' => isset($results['categories']) ? count($results['categories']) : 0,
            ],
            'results' => $results,
        ], 200);
    }
}
