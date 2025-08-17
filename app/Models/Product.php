<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'barcode',
        'category_id',
        'others',
        'sub_category_id',
        'brand_id',
        'unit_id',
        'currency_id',
        'image_path',
        'image_path1',
        'image_path2',
        'image_path3',
        'purchase_price',
        'wholesale_price',
        'retails_price',
        'intl_retails_price',
        'regular_price',
        'discount_type',
        'discount',
        'intl_discount',
        'description',
        'warrenty',
        'quantity',
        'discount_id',
        'warranty_id',
        'deleted',
        'user_id',
        'product_id',
        'kitchen_id',
        'have_variant',
        'have_product_variant',
        'serial',
        'color',
        'warranties_count',
        'manufactory_date',
        'expiry_date',
        'is_variable_weight',
        'minimum_stock',
        'product_type',
        'is_ecommerce',
        'color_code',
        'is_specification'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
