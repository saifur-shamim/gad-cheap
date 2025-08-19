<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $guarded = BaseModel::COMMON_GUARDED_FIELDS_SIMPLE;
 

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
