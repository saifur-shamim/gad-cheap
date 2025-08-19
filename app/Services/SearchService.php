<?php

namespace App\Services;

use App\Enums\BrandTypes;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;


/**
 * Service class for performing searches across different data types.
 */
class SearchService
{
    /**
     * Performs a search based on a given term, type, and limit.
     *
     * @param string $term The search term.
     * @param string $type The type of search (e.g., 'all', 'products', 'brands', 'categories').
     * @param int $limit The maximum number of results to return for each type.
     * @return array An array containing search results for products, brands, and categories.
     */
    public function performSearch(string $term, string $type = BrandTypes::ALL->value, int $limit = 10): array
    {
        $results = [
            'products' => [],
            'brands' => [],
            'categories' => [],
        ];

        // ---------------------
        // Products search
        // ---------------------
        if ($type === BrandTypes::ALL->value || $type === BrandTypes::PRODUCTS->value) {
            $productBuilder = Product::select([
                'id',
                'name',
                'barcode',
                'retails_price',
                'image_path',
                'category_id',
                'brand_id'
            ])->with([
                'category:id,name,image',
                'brand:id,name,image'
            ]);

            $productBuilder->where(function ($q) use ($term) {
                $like = '%' . $term . '%';
                $q->where('name', 'like', $like)
                    ->orWhere('barcode', 'like', $like)
                    ->orWhereHas('category', fn($qc) => $qc->where('name', 'like', $like))
                    ->orWhereHas('brand', fn($qb) => $qb->where('name', 'like', $like));
            });

            $products = $productBuilder->limit($limit)->get();
            $products->transform(fn($p) => tap(
                $p,
                fn($pp) => $pp->image_url = $pp->image_path ? url($pp->image_path) : null
            ));

            $results['products'] = $products;
        }

        // ---------------------
        // Brands search
        // ---------------------
        if ($type === BrandTypes::ALL->value || $type === BrandTypes::BRANDS->value) {
            $brandBuilder = Brand::select([
                'id',
                'name',
                'image'
            ])->where('status', true);

            $brandBuilder->where('name', 'like', '%' . $term . '%');
            $brands = $brandBuilder->limit($limit)->get();
            $brands->transform(fn($b) => tap(
                $b,
                fn($bb) => $bb->image_url = $bb->image ? url($bb->image) : null
            ));

            $results['brands'] = $brands;
        }

        // ---------------------
        // Categories search
        // ---------------------
        if ($type === BrandTypes::ALL->value || $type === BrandTypes::CATEGORIES->value) {
            $categoryBuilder = Category::select([
                'id',
                'name',
                'image'
            ]);

            $categoryBuilder->where('name', 'like', '%' . $term . '%');
            $categories = $categoryBuilder->limit($limit)->get();
            $categories->transform(fn($c) => tap(
                $c,
                fn($cc) => $cc->image_url = $cc->image ? url($cc->image) : null
            ));

            $results['categories'] = $categories;
        }

        return $results;
    }
}
