<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = BaseModel::COMMON_GUARDED_FIELDS_SIMPLE;

    // Event has many products
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
