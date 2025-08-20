<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = BaseModel::COMMON_GUARDED_FIELDS_SIMPLE;

    //  Self relation for sub-categories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Category can have many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
